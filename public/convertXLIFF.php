<?php


$content = file_get_contents(__DIR__.'/../translations/messages.en.xlf');

/*

Converts crazy xliffs files into readable yaml:

<?xml version="1.0" encoding="utf-8"?>
<xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
  <file source-language="zz" datatype="plaintext" original="file.ext">
    <body>
      <trans-unit id="1">
        <source>base.login.title</source>
        <target>Connect</target>
      </trans-unit>
      <trans-unit id="2">
        <source>base.logout.title</source>
        <target>Logout</target>
      </trans-unit>
      <trans-unit id="3">
        <source>base.login.as</source>
        <target>Connected as</target>
      </trans-unit>
      <trans-unit id="4">
        <source>base.login.using</source>
        <target>Connect using %provider%</target>
      </trans-unit>
      <trans-unit id="5">
        <source>base.unsuscribe.title</source>
        <target>You are going to unsuscribe.</target>
      </trans-unit>
      <trans-unit id="6">
        <source>base.unsuscribe.explain</source>
        <target>All data associated with your account will be</target>
      </trans-unit>
      <trans-unit id="7">
        <source>base.unsuscribe.removed</source>
        <target>removed</target>
      </trans-unit>
      <trans-unit id="8">
        <source>base.unsuscribe.confirm</source>
        <target>Confirm</target>
      </trans-unit>
      <trans-unit id="9">
        <source>base.locale.title</source>
        <target>Language</target>
      </trans-unit>
      <trans-unit id="10">
        <source>base.title</source>
        <target>My Empty Project</target>
      </trans-unit>
    </body>
  </file>
</xliff>

Becomes:

base:
    locale:
        title: Language
    login:
        as: 'Connected as'
        title: Connect
        using: 'Connect using %provider%'
    logout:
        title: Logout
    title: 'My Empty Project'
    unsuscribe:
        confirm: Confirm
        explain: 'All data associated with your account will be'
        removed: removed
        title: 'You are going to unsuscribe.'
*/

require 'vendor/autoload.php';

$xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
$yml = [];

foreach ($xml->file->body->{"trans-unit"} as $elem) {
    $key = $elem->source.'';
    $value = $elem->target.'';
    $tree = explode('.', $key);
    $cur = &$yml;
    foreach ($tree as $index => $part) {
        if (!isset($cur[$part])) {
            $cur[$part] = [];
        }
        if ($index + 1 == count($tree)) {
            $cur[$part] = $value;
        } else {
            if (!is_array($cur[$part])) {
                $conflict = join('.', array_slice($tree, 0, $index + 1));
                die("Translation {$key} has a translation conflicting: $conflict.\n");
            }
            $cur = &$cur[$part];
        }
    }
}

ksort_deep($yml);

function ksort_deep(&$array)
{
    ksort($array);
    foreach ($array as &$value) {
        if (is_array($value)) {
            ksort_deep($value);
        }
    }
}

echo Symfony\Component\Yaml\Yaml::dump($yml, 99);
