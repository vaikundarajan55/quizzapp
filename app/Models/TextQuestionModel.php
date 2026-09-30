<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TextQuestionModel
 *
 * Text Quiz questions (/text-quiz): question text + optional image,
 * 4 text answers with one correct. Tables: text_questions,
 * text_question_options (see database/upgrade_text_questions.sql).
 *
 * Returns the same array shape as QuizModel questions, with type 'text',
 * so the quiz page, submit scoring and admin views can share code.
 */
class TextQuestionModel extends Model
{
    public function getAllQuestions(): array
    {
        return $this->fetch(false);
    }

    public function getActiveQuestions(): array
    {
        return $this->fetch(true);
    }

    public function getQuestion(int $id): ?array
    {
        $row = $this->db->table('text_questions')->where('id', $id)->get()->getRowArray();
        if (!$row) return null;

        return $this->map($row, $this->fetchOptions([$id])[$id] ?? []);
    }

    public function createQuestion(array $data): int
    {
        $this->db->transStart();

        $sort = (int) $this->db->table('text_questions')->selectMax('sort')->get()->getRow()->sort + 1;
        $this->db->table('text_questions')->insert($this->toRow($data) + ['sort' => $sort]);
        $id = (int) $this->db->insertID();
        $this->saveOptions($id, $data['options'] ?? []);

        $this->db->transComplete();
        return $id;
    }

    public function updateQuestion(int $id, array $data): bool
    {
        $this->db->transStart();

        $this->db->table('text_questions')->where('id', $id)->update($this->toRow($data));
        $this->saveOptions($id, $data['options'] ?? []);

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteQuestion(int $id): bool
    {
        // text_question_options rows go with it via ON DELETE CASCADE
        return (bool) $this->db->table('text_questions')->where('id', $id)->delete();
    }

    public function toggleQuestion(int $id): bool
    {
        $this->db->table('text_questions')->where('id', $id)->set('active', '1 - active', false)->update();
        $row = $this->db->table('text_questions')->select('active')->where('id', $id)->get()->getRow();
        return $row ? (bool) $row->active : false;
    }

    // ---------------------------------------------------------------

    private function fetch(bool $activeOnly): array
    {
        $builder = $this->db->table('text_questions')->orderBy('sort')->orderBy('id');
        if ($activeOnly) {
            $builder->where('active', 1);
        }
        $rows = $builder->get()->getResultArray();
        if (!$rows) return [];

        $options = $this->fetchOptions(array_column($rows, 'id'));
        return array_map(fn($r) => $this->map($r, $options[$r['id']] ?? []), $rows);
    }

    /** @return array<int, array> options grouped by question_id, in index order */
    private function fetchOptions(array $questionIds): array
    {
        $rows = $this->db->table('text_question_options')
            ->whereIn('question_id', $questionIds)
            ->orderBy('question_id')->orderBy('sort')->orderBy('id')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($rows as $o) {
            $grouped[$o['question_id']][] = [
                'label'   => $o['label'],
                'correct' => (bool) $o['is_correct'],
            ];
        }
        return $grouped;
    }

    private function saveOptions(int $questionId, array $options): void
    {
        $this->db->table('text_question_options')->where('question_id', $questionId)->delete();

        $rows = [];
        foreach (array_values($options) as $i => $o) {
            $rows[] = [
                'question_id' => $questionId,
                'label'       => $o['label'] ?? '',
                'is_correct'  => !empty($o['correct']) ? 1 : 0,
                'sort'        => $i,
            ];
        }
        if ($rows) {
            $this->db->table('text_question_options')->insertBatch($rows);
        }
    }

    private function map(array $row, array $options): array
    {
        $correct = array_values(array_filter($options, fn($o) => $o['correct']))[0]['label'] ?? '';

        return [
            'id'                => (int) $row['id'],
            'type'              => 'text',
            'prompt'            => $row['prompt'],
            'answerLabel'       => $correct,
            'answerCardText'    => $row['answer_card_text'] ?? '',
            'questionImage'     => $row['question_image'],
            'questionImageCode' => $row['question_image_code'] ?? '',
            'options'           => $options,
            'active'            => (bool) $row['active'],
            'sort'              => (int) $row['sort'],
        ];
    }

    private function toRow(array $data): array
    {
        return [
            'prompt'              => $data['prompt'] ?? '',
            'answer_card_text'    => ($data['answerCardText'] ?? '') !== '' ? $data['answerCardText'] : null,
            'question_image'      => $data['questionImage'] ?? '',
            'question_image_code' => ($data['questionImageCode'] ?? '') !== '' ? $data['questionImageCode'] : null,
            'active'              => !empty($data['active']) ? 1 : 0,
        ];
    }
}
