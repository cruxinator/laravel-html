<?php


namespace Cruxinator\LaravelHtml\Tests;


use Cruxinator\LaravelHtml\Html;

class HtmlTest extends TestCase
{
    public function testUnescapedSource()
    {
        $url = "http://localhost?id=9n0206h58aki8mm4ratt24608&name=lain-cyberia-mix.png";

        $actual = app(Html::class)
            ->img()
            ->src($url)
            ->class("img-responsive")
            ->alt("")
            ->style('min-width: 60px; min-height: 60px;')
            ->toHtml();

        $expected = '<img class="img-responsive" src="http://localhost?id=9n0206h58aki8mm4ratt24608&amp;name=lain-cyberia-mix.png" alt style="min-width: 60px; min-height: 60px;">';
        $this->assertEquals($expected, $actual);
    }
}
