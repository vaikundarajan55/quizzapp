<?php

namespace App\Controllers;

use App\Libraries\ImageProcessor;
use App\Models\AdminModel;
use App\Models\QuizModel;
use App\Models\TextQuestionModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class AdminController extends Controller
{
    protected QuizModel $quiz;
    protected TextQuestionModel $textQuiz;

    public function __construct()
    {
        $this->quiz     = new QuizModel();
        $this->textQuiz = new TextQuestionModel();
        // Access is protected by the 'adminauth' filter (see Config/Routes.php)
    }

    // ---------------------------------------------------------------
    // Dashboard
    // ---------------------------------------------------------------

    public function index(): string
    {
        $stats    = $this->quiz->getStats();
        $settings = $this->quiz->getSettings();
        return view('admin/dashboard', compact('stats', 'settings'));
    }

    // ---------------------------------------------------------------
    // Questions
    // ---------------------------------------------------------------

    public function questions(): string
    {
        $questions = $this->quiz->getAllQuestions();
        $settings  = $this->quiz->getSettings();
        return view('admin/questions/index', compact('questions', 'settings'));
    }

    public function createQuestion(): string
    {
        $settings = $this->quiz->getSettings();
        return view('admin/questions/form', ['question' => null, 'settings' => $settings]);
    }

    public function storeQuestion()
    {
        try {
            $data = $this->buildQuestionData();
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $id = $this->quiz->createQuestion($data);

        return redirect()->to(base_url('admin/questions'))
            ->with('success', "Question #{$id} created successfully.");
    }

    public function editQuestion(int $id)
    {
        $question = $this->quiz->getQuestion($id);
        $settings = $this->quiz->getSettings();

        if (!$question) {
            return redirect()->to(base_url('admin/questions'))
                ->with('error', 'Question not found.');
        }
        return view('admin/questions/form', compact('question', 'settings'));
    }

    public function updateQuestion(int $id)
    {
        try {
            $data = $this->buildQuestionData();
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $this->quiz->updateQuestion($id, $data);

        return redirect()->to(base_url('admin/questions'))
            ->with('success', "Question #{$id} updated successfully.");
    }

    public function deleteQuestion(int $id)
    {
        $this->quiz->deleteQuestion($id);
        return redirect()->to(base_url('admin/questions'))
            ->with('success', "Question #{$id} deleted.");
    }

    public function reorderQuestions()
    {
        $order = $this->request->getPost('order') ?? [];
        foreach ($order as $sort => $id) {
            $this->quiz->updateQuestion((int)$id, ['sort' => (int)$sort + 1]);
        }
        return $this->response->setJSON(['success' => true]);
    }

    public function toggleQuestion(int $id)
    {
        $active = $this->quiz->toggleQuestion($id);
        return $this->response->setJSON(['success' => true, 'active' => $active]);
    }

    // ---------------------------------------------------------------
    // Text questions (tables text_questions / text_question_options)
    // ---------------------------------------------------------------

    public function textQuestions(): string
    {
        $questions = $this->textQuiz->getAllQuestions();
        return view('admin/text_questions/index', compact('questions'));
    }

    public function createTextQuestion(): string
    {
        return view('admin/text_questions/form', ['question' => null]);
    }

    public function storeTextQuestion()
    {
        try {
            $data = $this->buildTextQuestionData();
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        $id = $this->textQuiz->createQuestion($data);

        return redirect()->to(base_url('admin/text-questions'))
            ->with('success', "Text question #{$id} created successfully.");
    }

    public function editTextQuestion(int $id)
    {
        $question = $this->textQuiz->getQuestion($id);
        if (!$question) {
            return redirect()->to(base_url('admin/text-questions'))
                ->with('error', 'Text question not found.');
        }

        return view('admin/text_questions/form', compact('question'));
    }

    public function updateTextQuestion(int $id)
    {
        try {
            $data = $this->buildTextQuestionData();
        } catch (RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        $this->textQuiz->updateQuestion($id, $data);

        return redirect()->to(base_url('admin/text-questions'))
            ->with('success', "Text question #{$id} updated successfully.");
    }

    public function deleteTextQuestion(int $id)
    {
        $this->textQuiz->deleteQuestion($id);
        return redirect()->to(base_url('admin/text-questions'))
            ->with('success', "Text question #{$id} deleted.");
    }

    public function toggleTextQuestion(int $id)
    {
        $active = $this->textQuiz->toggleQuestion($id);
        return $this->response->setJSON(['success' => true, 'active' => $active]);
    }

    // ---------------------------------------------------------------
    // Results
    // ---------------------------------------------------------------

    public function results(): string
    {
        $results  = $this->quiz->getAllResults();
        $settings = $this->quiz->getSettings();
        return view('admin/results/index', compact('results', 'settings'));
    }

    public function resultDetail(string $token)
    {
        $result   = $this->quiz->getResult($token);
        $settings = $this->quiz->getSettings();

        if (!$result) {
            return redirect()->to(base_url('admin/results'))
                ->with('error', 'Result not found.');
        }

        return view('admin/results/detail', compact('result', 'settings'));
    }

    public function clearResults()
    {
        $this->quiz->clearResults();
        return redirect()->to(base_url('admin/results'))
            ->with('success', 'All results cleared.');
    }

    // ---------------------------------------------------------------
    // Settings
    // ---------------------------------------------------------------

    public function settings(): string
    {
        $settings = $this->quiz->getSettings();
        return view('admin/settings/index', compact('settings'));
    }

    public function saveSettings()
    {
        $post = $this->request->getPost();
        $settings = [
            'quiz_title'        => $post['quiz_title']        ?? 'Quiz',
            'quiz_subtitle'     => $post['quiz_subtitle']     ?? '',
            'voice_enabled'     => !empty($post['voice_enabled']),
            'show_answer_card'  => !empty($post['show_answer_card']),
            'allow_retry'       => !empty($post['allow_retry']),
            'theme_color'       => $post['theme_color']       ?? '#6c5ce7',
            'bg_type'           => $post['bg_type']           ?? 'ocean',
            'questions_per_quiz'=> (int)($post['questions_per_quiz'] ?? 0),
            'passing_score'     => (int)($post['passing_score']      ?? 70),
        ];
        $this->quiz->saveSettings($settings);

        return redirect()->to(base_url('admin/settings'))
            ->with('success', 'Settings saved successfully.');
    }

    public function changePassword()
    {
        $current = (string) $this->request->getPost('current_password');
        $new     = (string) $this->request->getPost('new_password');
        $confirm = (string) $this->request->getPost('confirm_password');

        $admins = new AdminModel();
        $back   = redirect()->to(base_url('admin/settings'));

        if (!$admins->verifyLogin((string) session('admin_username'), $current)) {
            return $back->with('error', 'Current password is incorrect.');
        }
        if (strlen($new) < 8) {
            return $back->with('error', 'New password must be at least 8 characters.');
        }
        if ($new !== $confirm) {
            return $back->with('error', 'New passwords do not match.');
        }

        $admins->changePassword((int) session('admin_id'), $new);
        return $back->with('success', 'Password updated successfully.');
    }

    // ---------------------------------------------------------------
    // Private helpers
    // ---------------------------------------------------------------

    private function buildQuestionData(): array
    {
        $post  = $this->request->getPost();
        $files = $this->request->getFiles();

        [$questionImage, $questionCode] = $this->resolveImage(
            'assets/questions',
            $post['questionImageMode']    ?? 'path',
            trim($post['questionImage']   ?? ''),
            $files['questionImageUpload'] ?? null,
            trim($post['questionImageCode'] ?? ''),
            $post['questionImageCanvas']  ?? '',
            $post['questionImageFormat']  ?? ''
        );
        if ($questionImage === '') {
            throw new RuntimeException('A question image is required.');
        }

        $options = [];
        $optLabels  = $post['opt_label']   ?? [];
        $optCorrect = $post['opt_correct'] ?? -1;

        foreach ($optLabels as $i => $label) {
            [$file, $code] = $this->resolveImage(
                'assets/options',
                $post['opt_mode'][$i]      ?? 'path',
                trim($post['opt_file'][$i] ?? ''),
                $files['opt_upload'][$i]   ?? null,
                trim($post['opt_code'][$i] ?? ''),
                $post['opt_canvas'][$i]    ?? '',
                $post['opt_format'][$i]    ?? ''
            );
            $options[] = [
                'label'   => $label,
                'file'    => $file,
                'code'    => $code,
                'correct' => ((int)$optCorrect === (int)$i),
            ];
        }

        return [
            'prompt'        => $post['prompt']        ?? '',
            'answerLabel'   => $post['answerLabel']   ?? '',
            'answerCardText'=> $post['answerCardText'] ?? '',
            'questionImage' => $questionImage,
            'questionImageCode' => $questionCode,
            'options'       => $options,
            'active'        => !empty($post['active']),
        ];
    }

    private function buildTextQuestionData(): array
    {
        $post    = $this->request->getPost();
        $prompt  = trim($post['prompt'] ?? '');
        $labels  = array_map('trim', array_slice(array_values($post['opt_label'] ?? []), 0, 4));
        $correct = (int) ($post['opt_correct'] ?? -1);

        if ($prompt === '') {
            throw new RuntimeException('Please enter the question.');
        }
        if (count($labels) !== 4 || in_array('', $labels, true)) {
            throw new RuntimeException('Please fill in all 4 options.');
        }
        if (!isset($labels[$correct])) {
            throw new RuntimeException('Please mark the correct option.');
        }

        // Optional question image: path / URL, upload or canvas code
        [$questionImage, $questionCode] = $this->resolveImage(
            'assets/questions',
            $post['questionImageMode']    ?? 'path',
            trim($post['questionImage']   ?? ''),
            $this->request->getFiles()['questionImageUpload'] ?? null,
            trim($post['questionImageCode'] ?? ''),
            $post['questionImageCanvas']  ?? '',
            $post['questionImageFormat']  ?? ''
        );

        $options = [];
        foreach ($labels as $i => $label) {
            $options[] = ['label' => $label, 'file' => '', 'code' => '', 'correct' => $i === $correct];
        }

        return [
            'prompt'         => $prompt,
            'answerCardText' => trim($post['answerCardText'] ?? ''),
            'questionImage'  => $questionImage,
            'questionImageCode' => $questionCode,
            'options'        => $options,
            'active'         => !empty($post['active']),
        ];
    }

    /**
     * Turns one image slot of the question form into [path, canvasCode].
     *
     * path   — use the typed path as-is
     * upload — save the uploaded file (keeps the current path if none chosen)
     * canvas — save the browser-rendered canvas; $canvasData is only sent when
     *          the code changed, otherwise the current path is kept
     *
     * A non-empty $format (png|jpg|webp) re-encodes the resulting image.
     */
    private function resolveImage(string $dir, string $mode, string $path, ?UploadedFile $upload,
                                  string $code, string $canvasData, string $format): array
    {
        $images = new ImageProcessor();

        if ($mode === 'upload' && $upload && $upload->isValid()) {
            return [$images->saveBytes(file_get_contents($upload->getTempName()), $dir, $format), ''];
        }
        if ($mode === 'canvas') {
            if ($code === '') {
                throw new RuntimeException('Canvas code is empty.');
            }
            if ($canvasData !== '') {
                return [$images->saveDataUrl($canvasData, $dir, $format), $code];
            }
        }

        $path = ($path !== '' && $format !== '') ? $images->convertExisting($path, $format) : $path;
        return [$path, $mode === 'canvas' ? $code : ''];
    }
}
