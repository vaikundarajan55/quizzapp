<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * QuizModel
 *
 * Manages quiz questions, options, submissions and settings in MySQL.
 * Schema + seed data: database/quiz_db.sql
 *
 * Public methods return the same array shapes the views use
 * (camelCase keys, options[] with file/label/correct).
 */
class QuizModel extends Model
{
    // ---------------------------------------------------------------
    // QUESTIONS
    // ---------------------------------------------------------------

    public function getAllQuestions(): array
    {
        return $this->fetchQuestions(false);
    }

    public function getActiveQuestions(): array
    {
        return $this->fetchQuestions(true);
    }

    public function getQuestion(int $id): ?array
    {
        $row = $this->db->table('questions')->where('id', $id)->get()->getRowArray();
        if (!$row) return null;

        return $this->mapQuestion($row, $this->fetchOptions([$id])[$id] ?? []);
    }

    public function createQuestion(array $data): int
    {
        $this->db->transStart();

        $sort = (int) $this->db->table('questions')->selectMax('sort')->get()->getRow()->sort + 1;
        $row  = $this->toQuestionRow($data) + ['active' => 1, 'sort' => $sort];
        $this->db->table('questions')->insert($row);
        $id = (int) $this->db->insertID();

        $this->saveOptions($id, $data['options'] ?? []);

        $this->db->transComplete();
        return $id;
    }

    public function updateQuestion(int $id, array $data): bool
    {
        $this->db->transStart();

        $row = $this->toQuestionRow($data);
        if ($row) {
            $this->db->table('questions')->where('id', $id)->update($row);
        }
        if (array_key_exists('options', $data)) {
            $this->saveOptions($id, $data['options']);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteQuestion(int $id): bool
    {
        // question_options rows go with it via ON DELETE CASCADE
        return (bool) $this->db->table('questions')->where('id', $id)->delete();
    }

    public function toggleQuestion(int $id): bool
    {
        $this->db->table('questions')->where('id', $id)->set('active', '1 - active', false)->update();
        $row = $this->db->table('questions')->select('active')->where('id', $id)->get()->getRow();
        return $row ? (bool) $row->active : false;
    }

    // ---------------------------------------------------------------
    // RESULTS / SUBMISSIONS
    // ---------------------------------------------------------------

    public function saveResult(array $result): string
    {
        $token = bin2hex(random_bytes(8));
        $this->db->table('quiz_results')->insert([
            'token'      => $token,
            'total'      => (int) ($result['total'] ?? 0),
            'correct'    => (int) ($result['correct'] ?? 0),
            'score'      => (float) ($result['score'] ?? 0),
            'elapsed'    => (int) ($result['elapsed'] ?? 0),
            'details'    => json_encode($result['details'] ?? []),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $token;
    }

    public function getResult(string $token): ?array
    {
        $row = $this->db->table('quiz_results')->where('token', $token)->get()->getRowArray();
        return $row ? $this->mapResult($row) : null;
    }

    public function getAllResults(): array
    {
        $rows = $this->db->table('quiz_results')->orderBy('id', 'DESC')->get()->getResultArray();
        return array_map([$this, 'mapResult'], $rows); // newest first
    }

    public function clearResults(): void
    {
        $this->db->table('quiz_results')->truncate();
    }

    public function getStats(): array
    {
        $agg = $this->db->table('quiz_results')
            ->select('COUNT(*) AS total, AVG(score) AS avg_score, MAX(score) AS high_score')
            ->get()->getRow();
        $recent = $this->db->table('quiz_results')->orderBy('id', 'DESC')->limit(5)->get()->getResultArray();

        return [
            'total_questions'  => $this->db->table('questions')->countAllResults(),
            'active_questions' => $this->db->table('questions')->where('active', 1)->countAllResults(),
            'total_attempts'   => (int) $agg->total,
            'avg_score'        => $agg->total ? round((float) $agg->avg_score, 1) : 0,
            'high_score'       => $agg->total ? (float) $agg->high_score : 0,
            'recent_results'   => array_map([$this, 'mapResult'], $recent),
        ];
    }

    // ---------------------------------------------------------------
    // SETTINGS
    // ---------------------------------------------------------------

    public function getSettings(): array
    {
        $settings = [];
        foreach ($this->db->table('settings')->get()->getResultArray() as $row) {
            $settings[$row['setting_key']] = json_decode($row['setting_value'], true);
        }
        return array_merge($this->defaultSettings(), $settings);
    }

    public function saveSettings(array $settings): void
    {
        $rows = [];
        foreach ($settings as $key => $value) {
            $rows[] = ['setting_key' => $key, 'setting_value' => json_encode($value)];
        }
        if ($rows) {
            $this->db->table('settings')->upsertBatch($rows);
        }
    }

    private function defaultSettings(): array
    {
        return [
            'quiz_title'        => 'Pickup & Drop the Correct Top Mark',
            'quiz_subtitle'     => 'IALA Buoyage System Quiz',
            'voice_enabled'     => true,
            'show_answer_card'  => true,
            'allow_retry'       => true,
            'theme_color'       => '#6c5ce7',
            'bg_type'           => 'ocean', // ocean | gradient | plain
            'questions_per_quiz'=> 0,       // 0 = all active
            'passing_score'     => 70,
        ];
    }

    // ---------------------------------------------------------------
    // PRIVATE HELPERS
    // ---------------------------------------------------------------

    private function fetchQuestions(bool $activeOnly): array
    {
        $builder = $this->db->table('questions')->orderBy('sort')->orderBy('id');
        if ($activeOnly) {
            $builder->where('active', 1);
        }
        $rows = $builder->get()->getResultArray();
        if (!$rows) return [];

        $options = $this->fetchOptions(array_column($rows, 'id'));
        return array_map(fn($r) => $this->mapQuestion($r, $options[$r['id']] ?? []), $rows);
    }

    /**
     * @return array<int, array> options grouped by question_id, in index order
     */
    private function fetchOptions(array $questionIds): array
    {
        $rows = $this->db->table('question_options')
            ->whereIn('question_id', $questionIds)
            ->orderBy('question_id')->orderBy('sort')->orderBy('id')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($rows as $o) {
            $grouped[$o['question_id']][] = [
                'file'    => $o['file'],
                'code'    => $o['code'] ?? '',
                'label'   => $o['label'],
                'correct' => (bool) $o['is_correct'],
            ];
        }
        return $grouped;
    }

    private function saveOptions(int $questionId, array $options): void
    {
        $this->db->table('question_options')->where('question_id', $questionId)->delete();

        $rows = [];
        foreach (array_values($options) as $i => $o) {
            $rows[] = [
                'question_id' => $questionId,
                'label'       => $o['label'] ?? '',
                'file'        => $o['file'] ?? '',
                'code'        => ($o['code'] ?? '') !== '' ? $o['code'] : null,
                'is_correct'  => !empty($o['correct']) ? 1 : 0,
                'sort'        => $i,
            ];
        }
        if ($rows) {
            $this->db->table('question_options')->insertBatch($rows);
        }
    }

    private function mapQuestion(array $row, array $options): array
    {
        return [
            'id'             => (int) $row['id'],
            'prompt'         => $row['prompt'],
            'answerLabel'    => $row['answer_label'],
            'answerCardText' => $row['answer_card_text'] ?? '',
            'questionImage'  => $row['question_image'],
            'questionImageCode' => $row['question_image_code'] ?? '',
            'options'        => $options,
            'active'         => (bool) $row['active'],
            'sort'           => (int) $row['sort'],
        ];
    }

    /**
     * Maps the camelCase keys the controllers pass in to DB columns.
     * Only keys present in $data are returned, so partial updates work.
     */
    private function toQuestionRow(array $data): array
    {
        $map = [
            'prompt'         => 'prompt',
            'answerLabel'    => 'answer_label',
            'answerCardText' => 'answer_card_text',
            'questionImage'  => 'question_image',
            'questionImageCode' => 'question_image_code',
            'active'         => 'active',
            'sort'           => 'sort',
        ];
        $row = [];
        foreach ($map as $key => $column) {
            if (array_key_exists($key, $data)) {
                $row[$column] = is_bool($data[$key]) ? (int) $data[$key] : $data[$key];
            }
        }
        return $row;
    }

    private function mapResult(array $row): array
    {
        return [
            'token'      => $row['token'],
            'total'      => (int) $row['total'],
            'correct'    => (int) $row['correct'],
            'score'      => (float) $row['score'],
            'elapsed'    => (int) $row['elapsed'],
            'details'    => json_decode($row['details'] ?? '[]', true) ?? [],
            'created_at' => $row['created_at'],
        ];
    }
}
