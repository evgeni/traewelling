<?php

namespace Tests\Feature\Status;

use App\Models\TrainCheckin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusBodyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider statusBodyProvider
     */
    public function testStatusBodySanitizesHtml($body, $htmlSanitized) {
        $status       = TrainCheckin::factory()->create()->status;
        $status->body = $body;
        $status->update();

        $this->get(route('statuses.get', $status))
             ->assertSee($htmlSanitized, escape: false);
    }

    public static function statusBodyProvider(): array {
        return [
            [
                'ab' . PHP_EOL . 'cd',
                'ab<br />' . PHP_EOL . 'cd'
            ],
            [
                '<script>alert(1)</script>',
                '&lt;script&gt;alert(1)&lt;/script&gt;'
            ],
            [
                'ab' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . 'cd',
                'ab<br />' . PHP_EOL . '<br />' . PHP_EOL . 'cd'
            ],
            [
                'ab' . PHP_EOL . PHP_EOL . PHP_EOL . 'cd' . PHP_EOL . PHP_EOL . PHP_EOL . 'ef',
                'ab<br />' . PHP_EOL . '<br />' . PHP_EOL . 'cd<br />' . PHP_EOL . '<br />' . PHP_EOL . 'ef'
            ],
        ];
    }
}
