<?php

namespace App\Controllers;

use App\Models\QuizModel;
use App\Models\TextQuestionModel;
use CodeIgniter\Controller;

class WebsiteController extends Controller
{
    protected QuizModel $quiz;

    public function __construct()
    {
        $this->quiz = new QuizModel();
    }

    /**
     * Home / landing page
     */
    public function index(): string
    {
        $settings = $this->quiz->getSettings();
        $stats    = $this->quiz->getStats();
        return view('website/home', compact('settings', 'stats'));
    }

    /**
     * Quiz page — drag-and-drop image questions
     */
    public function quiz(?int $page = null): string
    {
        return $this->renderQuiz('image');
    }

    /**
     * Text quiz page — question image + 4 text answers
     */
    public function textQuiz(): string
    {
        return $this->renderQuiz('text');
    }

    private function renderQuiz(string $type): string
    {
        $settings  = $this->quiz->getSettings();
        $questions = $this->activeQuestions($type);

        // Limit questions if setting is applied
        $limit = (int)($settings['questions_per_quiz'] ?? 0);
        if ($limit > 0 && $limit < count($questions)) {
            $questions = array_slice($questions, 0, $limit);
        }

        return view('website/quiz', compact('settings', 'questions', 'type'));
    }

    /** Active questions of one quiz: 'image' (drag-and-drop) or 'text'. */
    private function activeQuestions(string $type): array
    {
        return $type === 'text'
            ? (new TextQuestionModel())->getActiveQuestions()
            : $this->quiz->getActiveQuestions();
    }

    /**
     * Handle quiz submission (AJAX POST from the quiz page)
     */
    public function submit()
    {
        $request = $this->request;
        $answers = $request->getPost('answers') ?? [];
        if (is_string($answers)) {
            // quiz.js posts answers as a JSON string: {"questionId": optionIndex}
            $answers = json_decode($answers, true) ?? [];
        }
        $elapsed = (int)($request->getPost('elapsed') ?? 0);

        // Score only the questions of the quiz that was taken
        $type      = $request->getPost('type') === 'text' ? 'text' : 'image';
        $questions = $this->activeQuestions($type);
        $total     = count($questions);
        $correct   = 0;
        $details   = [];

        foreach ($questions as $q) {
            $qid      = $q['id'];
            $answered = $answers[$qid] ?? null;
            $isCorrect = false;

            if ($answered !== null) {
                $optIdx   = (int)$answered;
                $isCorrect = !empty($q['options'][$optIdx]['correct']);
            }

            if ($isCorrect) $correct++;
            $details[] = [
                'id'           => $qid,
                'prompt'       => $q['prompt'],
                'answerLabel'  => $q['answerLabel'],
                'answered'     => $answered !== null,
                'optionIndex'  => $answered,
                'correct'      => $isCorrect,
                'correctLabel' => array_values(array_filter($q['options'], fn($o) => !empty($o['correct'])))[0]['label'] ?? '',
            ];
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 1) : 0;

        $token = $this->quiz->saveResult([
            'total'   => $total,
            'correct' => $correct,
            'score'   => $score,
            'elapsed' => $elapsed,
            'details' => $details,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'token'   => $token,
            'score'   => $score,
            'correct' => $correct,
            'total'   => $total,
        ]);
    }

    /**
     * Results page — show result by token
     */
    public function results(string $token): string
    {
        $result   = $this->quiz->getResult($token);
        $settings = $this->quiz->getSettings();

        if (!$result) {
            return view('errors/404', ['message' => 'Result not found.']);
        }

        return view('website/results', compact('result', 'settings'));
    }

    /**
     * About page
     */
    public function about(): string
    {
        $settings = $this->quiz->getSettings();
        return view('website/about', compact('settings'));
    }
}
