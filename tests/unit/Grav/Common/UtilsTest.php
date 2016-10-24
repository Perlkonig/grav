<?php

use Codeception\Util\Fixtures;
use Grav\Common\Grav;
use Grav\Common\Utils;

/**
 * Class UtilsTest
 */
class UtilsTest extends \Codeception\TestCase\Test
{
    /** @var Grav $grav */
    protected $grav;

    protected function _before()
    {
        $grav = Fixtures::get('grav');
        $this->grav = $grav();
    }

    protected function _after()
    {
    }

    public function testStartsWith()
    {
        $this->assertTrue(Utils::startsWith('english', 'en'));
        $this->assertTrue(Utils::startsWith('English', 'En'));
        $this->assertTrue(Utils::startsWith('ENGLISH', 'EN'));
        $this->assertTrue(Utils::startsWith('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'EN'));

        $this->assertFalse(Utils::startsWith('english', 'En'));
        $this->assertFalse(Utils::startsWith('English', 'EN'));
        $this->assertFalse(Utils::startsWith('ENGLISH', 'en'));
        $this->assertFalse(Utils::startsWith('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'e'));
    }

    public function testEndsWith()
    {
        $this->assertTrue(Utils::endsWith('english', 'sh'));
        $this->assertTrue(Utils::endsWith('EngliSh', 'Sh'));
        $this->assertTrue(Utils::endsWith('ENGLISH', 'SH'));
        $this->assertTrue(Utils::endsWith('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'ENGLISH'));

        $this->assertFalse(Utils::endsWith('english', 'de'));
        $this->assertFalse(Utils::endsWith('EngliSh', 'sh'));
        $this->assertFalse(Utils::endsWith('ENGLISH', 'Sh'));
        $this->assertFalse(Utils::endsWith('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'DEUSTCH'));
    }

    public function testContains()
    {
        $this->assertTrue(Utils::contains('english', 'nglis'));
        $this->assertTrue(Utils::contains('EngliSh', 'gliSh'));
        $this->assertTrue(Utils::contains('ENGLISH', 'ENGLI'));
        $this->assertTrue(Utils::contains('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'ENGLISH'));

        $this->assertFalse(Utils::contains('EngliSh', 'GLI'));
        $this->assertFalse(Utils::contains('EngliSh', 'English'));
        $this->assertFalse(Utils::contains('ENGLISH', 'SCH'));
        $this->assertFalse(Utils::contains('ENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISHENGLISH',
            'DEUSTCH'));
    }

    public function testSubstrToString()
    {
        $this->assertEquals('en', Utils::substrToString('english', 'glish'));
        $this->assertEquals('english', Utils::substrToString('english', 'test'));
        $this->assertNotEquals('en', Utils::substrToString('english', 'lish'));
    }

    public function testMergeObjects()
    {
        $obj1 = new stdClass();
        $obj1->test1 = 'x';
        $obj2 = new stdClass();
        $obj2->test2 = 'y';

        $objMerged = Utils::mergeObjects($obj1, $obj2);

        $this->assertObjectHasAttribute('test1', $objMerged);
        $this->assertObjectHasAttribute('test2', $objMerged);
    }

    public function testDateFormats()
    {
        $dateFormats = Utils::dateFormats();
        $this->assertTrue(is_array($dateFormats));
        $this->assertContainsOnly('string', $dateFormats);

        $default_format = $this->grav['config']->get('system.pages.dateformat.default');

        if ($default_format !== null) {
            $this->assertTrue(isset($dateFormats[$default_format]));
        }
    }

    public function testTruncate()
    {
        $this->assertEquals('engli' . '&hellip;', Utils::truncate('english', 5));
        $this->assertEquals('english', Utils::truncate('english'));
        $this->assertEquals('This is a string to truncate', Utils::truncate('This is a string to truncate'));
        $this->assertEquals('Th' . '&hellip;', Utils::truncate('This is a string to truncate', 2));
        $this->assertEquals('engli' . '...', Utils::truncate('english', 5, true, " ", "..."));
        $this->assertEquals('english', Utils::truncate('english'));
        $this->assertEquals('This is a string to truncate', Utils::truncate('This is a string to truncate'));
        $this->assertEquals('This ', Utils::truncate('This is a string to truncate', 3, true));
        $this->assertEquals('<input ', Utils::truncate('<input type="file" id="file" multiple />', 6, true));

    }

    public function testWordwrap() {
        $teststr = 'Lorem ipsum dolor sit amet, solet recusabo concludaturque eu duo, ei posse error albucius eum. Sit no quas populo accusamus.';
        $r80 = "Lorem ipsum dolor sit amet, solet recusabo concludaturque eu duo, ei posse error\nalbucius eum. Sit no quas populo accusamus.";
        $r40 = "Lorem ipsum dolor sit amet, solet\nrecusabo concludaturque eu duo, ei posse\nerror albucius eum. Sit no quas populo\naccusamus.";
        $r39 = "Lorem ipsum dolor sit amet, solet\nrecusabo concludaturque eu duo, ei\nposse error albucius eum. Sit no quas\npopulo accusamus.";
        $r10 = "Lorem\nipsum\ndolor sit\namet,\nsolet\nrecusabo\nconcludaturque\neu duo, ei\nposse\nerror\nalbucius\neum. Sit\nno quas\npopulo\naccusamus.";

        $mb_teststr = 'Лорем ипсум долор сит амет, цум еа иллуд платонем инцидеринт, еу хис феугиат сцаевола! Фацилис диссентиет яуо еи, еум ет.';
        $mb_r80 = "Лорем ипсум долор сит амет, цум еа иллуд платонем инцидеринт, еу хис феугиат\nсцаевола! Фацилис диссентиет яуо еи, еум ет.";
        $mb_r40 = "Лорем ипсум долор сит амет, цум еа иллуд\nплатонем инцидеринт, еу хис феугиат\nсцаевола! Фацилис диссентиет яуо еи, еум\nет.";
        $mb_r39 = "Лорем ипсум долор сит амет, цум еа\nиллуд платонем инцидеринт, еу хис\nфеугиат сцаевола! Фацилис диссентиет\nяуо еи, еум ет.";
        $mb_r10 = "Лорем\nипсум\nдолор сит\nамет, цум\nеа иллуд\nплатонем\nинцидеринт,\nеу хис\nфеугиат\nсцаевола!\nФацилис\nдиссентиет\nяуо еи,\nеум ет.";

        $this->assertEquals($r80, Utils::wordwrap($teststr, 80));
        $this->assertEquals($r40, Utils::wordwrap($teststr, 40));
        $this->assertEquals($r39, Utils::wordwrap($teststr, 39));
        $this->assertEquals($r10, Utils::wordwrap($teststr, 10));
        $this->assertEquals($mb_r80, Utils::wordwrap($mb_teststr, 80));
        $this->assertEquals($mb_r40, Utils::wordwrap($mb_teststr, 40));
        $this->assertEquals($mb_r39, Utils::wordwrap($mb_teststr, 39));
        $this->assertEquals($mb_r10, Utils::wordwrap($mb_teststr, 10));
    }

    public function testSafeTruncate()
    {
        $this->assertEquals('This ', Utils::safeTruncate('This is a string to truncate', 1));
        $this->assertEquals('This ', Utils::safeTruncate('This is a string to truncate', 4));
        $this->assertEquals('This is ', Utils::safeTruncate('This is a string to truncate', 5));
    }

    public function testTruncateHtml()
    {
        $this->assertEquals('<p>T...</p>', Utils::truncateHtml('<p>This is a string to truncate</p>', 1));
        $this->assertEquals('<p>This...</p>', Utils::truncateHtml('<p>This is a string to truncate</p>', 4));
        $this->assertEquals('<p>This is a...</p>', Utils::truncateHtml('<p>This is a string to truncate</p>', 10));
        $this->assertEquals('<p>This is a string to truncate</p>', Utils::truncateHtml('<p>This is a string to truncate</p>', 100));
        $this->assertEquals('<input type="file" id="file" multiple>', Utils::truncateHtml('<input type="file" id="file" multiple />', 6));
        $this->assertEquals('<ol><li>item 1 <i>so...</i></li></ol>', Utils::truncateHtml('<ol><li>item 1 <i>something</i></li><li>item 2 <strong>bold</strong></li></ol>', 10));
    }

    public function testSafeTruncateHtml()
    {
        $this->assertEquals('<p>This...</p>', Utils::safeTruncateHtml('<p>This is a string to truncate</p>', 1));
        $this->assertEquals('<p>This is...</p>', Utils::safeTruncateHtml('<p>This is a string to truncate</p>', 2));
        $this->assertEquals('<p>This is a string to...</p>', Utils::safeTruncateHtml('<p>This is a string to truncate</p>', 5));
        $this->assertEquals('<p>This is a string to truncate</p>', Utils::safeTruncateHtml('<p>This is a string to truncate</p>', 20));
        $this->assertEquals('<input type="file" id="file" multiple>', Utils::safeTruncateHtml('<input type="file" id="file" multiple />', 6));
        $this->assertEquals('<ol><li>item 1 <i>something</i></li><li>item 2...</li></ol>', Utils::safeTruncateHtml('<ol><li>item 1 <i>something</i></li><li>item 2 <strong>bold</strong></li></ol>', 5));
    }

    public function testGenerateRandomString()
    {
        $this->assertNotEquals(Utils::generateRandomString(), Utils::generateRandomString());
        $this->assertNotEquals(Utils::generateRandomString(20), Utils::generateRandomString(20));
    }

    public function download()
    {

    }

    public function testGetMimeType()
    {
        $this->assertEquals('application/octet-stream', Utils::getMimeType(''));
        $this->assertEquals('image/jpeg', Utils::getMimeType('jpg'));
        $this->assertEquals('image/png', Utils::getMimeType('png'));
        $this->assertEquals('text/plain', Utils::getMimeType('txt'));
        $this->assertEquals('application/msword', Utils::getMimeType('doc'));
    }

    public function testNormalizePath()
    {
        $this->assertEquals('/test', Utils::normalizePath('/test'));
        $this->assertEquals('test', Utils::normalizePath('test'));
        $this->assertEquals('test', Utils::normalizePath('../test'));
        $this->assertEquals('/test', Utils::normalizePath('/../test'));
        $this->assertEquals('/test2', Utils::normalizePath('/test/../test2'));
        $this->assertEquals('/test/test2', Utils::normalizePath('/test/./test2'));
    }

    public function testIsFunctionDisabled()
    {
        $disabledFunctions = explode(',', ini_get('disable_functions'));

        if ($disabledFunctions[0]) {
            $this->assertEquals(Utils::isFunctionDisabled($disabledFunctions[0]), true);
        }
    }

    public function testTimezones()
    {
        $timezones = Utils::timezones();

        $this->assertTrue(is_array($timezones));
        $this->assertContainsOnly('string', $timezones);
    }

    public function testArrayFilterRecursive()
    {
        $array = [
            'test'  => '',
            'test2' => 'test2'
        ];

        $array = Utils::arrayFilterRecursive($array, function ($k, $v) {
            return !(is_null($v) || $v === '');
        });

        $this->assertContainsOnly('string', $array);
        $this->assertFalse(isset($array['test']));
        $this->assertTrue(isset($array['test2']));
        $this->assertEquals('test2', $array['test2']);
    }

    public function testPathPrefixedByLangCode()
    {
        $languagesEnabled = $this->grav['config']->get('system.languages.supported', []);
        $arrayOfLanguages = ['en', 'de', 'it', 'es', 'dk', 'el'];
        $languagesNotEnabled = array_diff($arrayOfLanguages, $languagesEnabled);
        $oneLanguageNotEnabled = reset($languagesNotEnabled);

        if (count($languagesEnabled)) {
            $this->assertTrue(Utils::pathPrefixedByLangCode('/' . $languagesEnabled[0] . '/test'));
        }

        $this->assertFalse(Utils::pathPrefixedByLangCode('/' . $oneLanguageNotEnabled . '/test'));
        $this->assertFalse(Utils::pathPrefixedByLangCode('/test'));
        $this->assertFalse(Utils::pathPrefixedByLangCode('/xx'));
        $this->assertFalse(Utils::pathPrefixedByLangCode('/xx/'));
        $this->assertFalse(Utils::pathPrefixedByLangCode('/'));
    }

    public function testDate2timestamp()
    {
        $timestamp = strtotime('10 September 2000');
        $this->assertSame($timestamp, Utils::date2timestamp('10 September 2000'));
        $this->assertSame($timestamp, Utils::date2timestamp('2000-09-10 00:00:00'));
    }

    public function testResolve()
    {
        $array = [
            'test' => [
                'test2' => 'test2Value'
            ]
        ];

        $this->assertEquals('test2Value', Utils::resolve($array, 'test.test2'));
    }

    public function testGetDotNotation()
    {
        $array = [
            'test' => [
                'test2' => 'test2Value',
                'test3' => [
                    'test4' => 'test4Value'
                ]
            ]
        ];

        $this->assertEquals('test2Value', Utils::getDotNotation($array, 'test.test2'));
        $this->assertEquals('test4Value', Utils::getDotNotation($array, 'test.test3.test4'));
        $this->assertEquals('defaultValue', Utils::getDotNotation($array, 'test.non_existent', 'defaultValue'));
    }

    public function testSetDotNotation()
    {
        $array = [
            'test' => [
                'test2' => 'test2Value',
                'test3' => [
                    'test4' => 'test4Value'
                ]
            ]
        ];

        $new = [
            'test1' => 'test1Value'
        ];

        Utils::setDotNotation($array, 'test.test3.test4' , $new);
        $this->assertEquals('test1Value', $array['test']['test3']['test4']['test1']);
    }

    public function testIsPositive()
    {
        $this->assertTrue(Utils::isPositive(true));
        $this->assertTrue(Utils::isPositive(1));
        $this->assertTrue(Utils::isPositive('1'));
        $this->assertTrue(Utils::isPositive('yes'));
        $this->assertTrue(Utils::isPositive('on'));
        $this->assertTrue(Utils::isPositive('true'));
        $this->assertFalse(Utils::isPositive(false));
        $this->assertFalse(Utils::isPositive(0));
        $this->assertFalse(Utils::isPositive('0'));
        $this->assertFalse(Utils::isPositive('no'));
        $this->assertFalse(Utils::isPositive('off'));
        $this->assertFalse(Utils::isPositive('false'));
        $this->assertFalse(Utils::isPositive('some'));
        $this->assertFalse(Utils::isPositive(2));
    }

    public function testGetNonce()
    {
        $this->assertTrue(is_string(Utils::getNonce('test-action')));
        $this->assertTrue(is_string(Utils::getNonce('test-action', true)));
        $this->assertSame(Utils::getNonce('test-action'), Utils::getNonce('test-action'));
        $this->assertNotSame(Utils::getNonce('test-action'), Utils::getNonce('test-action2'));
    }

    public function testVerifyNonce()
    {
        $this->assertTrue(Utils::verifyNonce(Utils::getNonce('test-action'), 'test-action'));
    }
}
