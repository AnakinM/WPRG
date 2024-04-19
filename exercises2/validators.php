<?php
function validateNumber($field)
{
    return empty($field) || !is_numeric($field);
}

function validateString($field)
{
    return empty($field) || !ctype_alpha($field);
}

function validateEmail($field)
{
    return empty($field) || !filter_var($field, FILTER_VALIDATE_EMAIL);
}

function validateLength($field, $min, $max)
{
    return empty($field) || strlen($field) < $min || strlen($field) > $max;
}

function validateDate($field)
{
    return empty($field) || !date_create_from_format('Y-m-d', $field);
}

function validateTime($field)
{
    return empty($field) || DateTime::createFromFormat('H:i', $field) === false;
}

function validateCreditCard($field)
{
    return empty($field) || !is_numeric($field) || strlen($field) != 16;
}

function validateSecurityCode($field)
{
    return empty($field) || !is_numeric($field) || strlen($field) != 3;
}

function validateExpirationDate($field)
{
    return empty($field) || !date_create_from_format('m/Y', $field);
}
