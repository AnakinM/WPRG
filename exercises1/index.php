<?php

// Exercise 1
function fruits()
{
    $fruits = array("jabłko", "banan", "pomarańcza");

    foreach ($fruits as $fruit) {
        $backwards = '';

        for ($i = strlen($fruit) - 1; $i >= 0; $i--) {
            $backwards .= $fruit[$i];
        }

        $startsWithP = $fruit[0] == 'p' || $fruit[0] == 'P';

        if ($startsWithP) {
            echo $backwards . ", starts with p\n";
        } else {
            echo $backwards . ", doesn't start with p\n";
        }
    }

    echo "\n";
}

// Exercise 2
// print all prime numbers from a given range
function primeNumbers($start, $end)
{
    for ($i = $start; $i <= $end; $i++) {
        $isPrime = true;

        if ($i == 1) {
            $isPrime = false;
        }

        for ($j = 2; $j <= sqrt($i); $j++) {
            if ($i % $j == 0) {
                $isPrime = false;
                break;
            }
        }

        if ($isPrime) {
            echo $i . "\n";
        }
    }

    echo "\n";
}

// Exercise 3
function fibonacci($n)
{
    $fib = array(0, 1);

    for ($i = 2; $i < $n; $i++) {
        $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
    }

    for ($i = 0; $i < $n; $i++) {
        if ($fib[$i] % 2 != 0) {
            echo $i + 1 . ". " . $fib[$i] . "\n";
        }
    }

    echo "\n";
}

// Exercise 4
function textToArray($text)
{

    $textArray = explode(" ", $text);
    $cleanedArray = array();

    foreach ($textArray as $word) {
        $cleanedWord = preg_replace("/[^a-zA-Z0-9]+/", "", $word);

        if ($cleanedWord != "") {
            array_push($cleanedArray, $cleanedWord);
        }
    }

    $associativeArray = array();

    for ($i = 0; $i < count($cleanedArray); $i++) {
        if ($i % 2 == 0) {
            $associativeArray[$cleanedArray[$i]] = $cleanedArray[$i + 1];
        }
    }

    print_r($associativeArray);

    echo "\n";
}



fruits();
primeNumbers(1, 100);
fibonacci(10);
textToArray("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
galley of type and scrambled it to make a type specimen book. It has survived not only five
centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was
popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
and more recently with desktop publishing software like Aldus PageMaker including versions of
Lorem Ipsum.");
