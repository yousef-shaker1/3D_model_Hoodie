<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Color;
use App\Models\Governorate;

class AiChatController extends Controller
{
    public function handle(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'array',
        ]);

        // نجيب بيانات حقيقية من الداتابيز عشان الرد يكون دقيق ومحدّث
        $colors = Color::all(['name', 'hex_code']);
        $governorates = Governorate::all(['name', 'shipping_price']);

        $colorsList = $colors->map(fn($c) => "{$c->name} ({$c->hex_code})")->implode('، ');
        $shippingList = $governorates->map(fn($g) => "{$g->name}: {$g->shipping_price} جنيه")->implode('، ');

        $systemPrompt = <<<PROMPT
أنت مساعد خدمة عملاء لموقع WearCraft، متجر لتصميم هودي مخصص بتقنية 3D.

معلومات عن الموقع:
- ألوان الهودي المتاحة: {$colorsList}.
- المقاسات: S, M, L, XL, XXL (حسب توفر كل لون على حدة).
- المستخدم يقدر يضيف لوجو من مكتبة جاهزة، أو يرفع لوجو من جهازه (بيتشال من الخلفية أوتوماتيك)، وكمان يضيف نص عربي بخطوط متنوعة وتشكيل تزييني اختياري.
- المستخدم يقدر يحرك/يكبر/يدور اللوجو أو النص، ويشوف التصميم من 4 جهات (وش/ظهر/يمين/يسار).
- أسعار الشحن حسب المحافظة: {$shippingList}.
- لإتمام الطلب: يضغط "إرسال الطلب" ويملأ الاسم والتليفون والعنوان والمحافظة والمقاس، وفيه كود خصم اختياري.

القواعد:
- رد دايمًا بلهجة مصرية عامية ودودة ومختصرة (سطرين لثلاثة كحد أقصى).
- لو مش عارف إجابة سؤال معين، قول إنك هتوصله بخدمة العملاء، ومتخترعش معلومة.
- لو المستخدم طلب منك تنفذ حاجة فعلية في الصفحة، رجّع "action" مناسب من الأنواع دي فقط:
  - تغيير اللون: {"type": "change_color", "color": "#hex"} (استخدم الـ hex بالظبط زي المذكور فوق)
  - اختيار مقاس: {"type": "select_size", "size": "M"}
  - فتح نافذة الطلب: {"type": "open_order_modal"}
  - فتح نافذة تصدير الصور: {"type": "open_export_modal"}
  - من غير إجراء: null

لازم ترد بصيغة JSON فقط بدون أي نص إضافي قبلها أو بعدها، بالشكل ده بالظبط:
{"reply": "نص الرد هنا", "action": null}
PROMPT;

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($request->input('history', []) as $m) {
            if (isset($m['role'], $m['content']) && in_array($m['role'], ['user', 'assistant'])) {
                $messages[] = ['role' => $m['role'], 'content' => (string) $m['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $request->input('message')];

        try {
            $response = Http::withToken(config('services.groq.api_key'))
                ->timeout(20)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config('services.groq.model'),
                    'messages' => $messages,
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.4,
                    'max_completion_tokens' => 400,
                ]);

            if (!$response->successful()) {
                report(new \Exception('Groq API error: ' . $response->body()));
                return response()->json(['reply' => 'المساعد مش متاح دلوقتي، حاول تاني بعد شوية 🙏']);
            }

            $content = $response->json('choices.0.message.content');
            $parsed = json_decode($content, true);

            if (!is_array($parsed) || !isset($parsed['reply'])) {
                return response()->json(['reply' => is_string($content) ? $content : 'حصل خطأ بسيط، حاول تاني.']);
            }

            return response()->json([
                'reply'  => $parsed['reply'],
                'action' => $parsed['action'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['reply' => 'حصل خطأ في الاتصال بالمساعد، حاول تاني.']);
        }
    }
}