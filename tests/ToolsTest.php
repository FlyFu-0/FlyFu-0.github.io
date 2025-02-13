<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require '../app/helpers/Tools.php';

class ToolsTest extends TestCase
{
	public function testBbBold()
	{
		$actual = \Helpers\Tools::bbcode_handler('[b]Hello[/b] world!');

		$this->assertEquals('<b>Hello</b> world!', $actual);
	}

	public function testBbBoldNested()
	{
		$actual = \Helpers\Tools::bbcode_handler('[b]Hello [b]Earth[/b] [/b] world!');

		$this->assertNotEquals('<b>Hello <b>Earth</b> </b> world!', $actual);
	}

	public function testBbBR()
	{
		$actual = \Helpers\Tools::bbcode_handler('Hello[br]world!');

		$this->assertEquals('Hello<br />world!', $actual);
	}

	public function testBbBRNestedBold()
	{
		$actual = \Helpers\Tools::bbcode_handler('[b]Hello [br] [/b] world!');

		$this->assertEquals('<b>Hello <br /> </b> world!', $actual);
	}

	public function testBbI()
	{
		$actual = \Helpers\Tools::bbcode_handler('[i]Hello[/i] world!');

		$this->assertEquals('<i>Hello</i> world!', $actual);
	}

	public function testBbUnderline()
	{
		$actual = \Helpers\Tools::bbcode_handler('[u]Hello[/u] world!');

		$this->assertEquals("<span style='text-decoration:underline'>Hello</span> world!", $actual);
	}
	public function testBbUnderlineNestedBold()
	{
		$actual = \Helpers\Tools::bbcode_handler('[b]Meow [u]Hello[/u] [/b] world!');

		$this->assertEquals("<b>Meow <span style='text-decoration:underline'>Hello</span> </b> world!", $actual);
	}

	public function testBbQuote()
	{
		$actual = \Helpers\Tools::bbcode_handler('Wow, [q]Hello [/q] world!');

		$this->assertEquals('Wow, <q>Hello </q> world!', $actual);
	}

	public function testBbURL()
	{
		$actual = \Helpers\Tools::bbcode_handler('[url]https://google.com[/url] world');

		$this->assertEquals("<a href='https://google.com'>https://google.com</a> world", $actual);
	}

	public function testBbURLWithTitle()
	{
		$actual = \Helpers\Tools::bbcode_handler('[url=https://google.com]Hello[/url] world');

		$this->assertEquals("<a href='https://google.com'>Hello</a> world", $actual);
	}

	public function testBbImg()
	{
		$imgLink = 'https://www.google.ru/url?sa=i&url=https%3A%2F%2Fwww.freepik.com%2Fphotos%2Fsea&psig=AOvVaw29owEOVxgsvVFRiVSn8ojP&ust=1739349862961000&source=images&cd=vfe&opi=89978449&ved=0CBQQjRxqFwoTCOiu8bKdu4sDFQAAAAAdAAAAABAE';

		$actual = \Helpers\Tools::bbcode_handler("[img]{$imgLink}[/img] world");

		$this->assertEquals("<img src='$imgLink' alt = 'Изображение' width='120px' height='120px' /> world", $actual);
	}

	public function testBbSize()
	{
		$actual = \Helpers\Tools::bbcode_handler("[size=200]Hello[/size] world");

		$this->assertEquals("<span style='font-size:200%'>Hello</span> world", $actual);
	}

	public function testBbColor()
	{
		$actual = \Helpers\Tools::bbcode_handler("[color=red]Hello[/color] world");

		$this->assertEquals("<span style='color:red'>Hello</span> world", $actual);
	}

	public function testBbList()
	{
		$actual = \Helpers\Tools::bbcode_handler("[list]Hello[/list] world");

		$this->assertEquals("<ul>Hello</ul> world", $actual);
	}

	public function testBbOrderedList()
	{
		$actual = \Helpers\Tools::bbcode_handler("[listn]Hello[/listn] world");

		$this->assertEquals("<ol>Hello</ol> world", $actual);
	}

	public function testBbLi()
	{
		$actual = \Helpers\Tools::bbcode_handler("[*]Hello[/*] world");

		$this->assertEquals("<li>Hello</li> world", $actual);
	}
}
