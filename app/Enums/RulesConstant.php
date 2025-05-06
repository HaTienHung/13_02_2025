<?php

namespace App\Enums;

class RulesConstant
{
  const COLON = ':';
  const PIPELINE = '|';
  const RULE_VALUE = 'value';
  const KEY_ATTRIBUTE = 'attribute';
  const RULE_UPLOADED = 'validation.uploaded';


  //key rules
  const RULE_REQUIRED = 'required';
  const RULE_STRING = 'string';
  const RULE_IN = 'in';
  const RULE_NOT_IN = 'not_in';
  const RULE_MIN = 'min';
  const RULE_URL = 'url';
  const RULE_MAX = 'max';
  const RULE_INTEGER = 'integer';
  const RULE_MIMES = 'mimes';
  const RULE_EMAIL = 'email';
  const RULE_UNIQUE = 'unique';
  const RULE_JSON = 'json';
  const RULE_IMAGE = 'image';
  const RULE_ARRAY = 'array';
  const RULE_BOOLEAN = 'boolean';
  const RULE_REGEX = 'regex';
  const RULE_EXISTS = 'exists';
  const RULE_SAME = 'same';
  const RULE_NUMERIC = 'numeric';
  const RULE_AFTER = 'after';
  const RULE_DATE = 'date';
  const RULE_DATE_FORMAT = 'date_format';

  //message for rules
  const MESSAGE_RULE_REQUIRED = 'messages.errors.rules.required';
  const MESSAGE_RULE_STRING = 'messages.errors.rules.string';
  const MESSAGE_RULE_IN = 'messages.errors.rules.in';
  const MESSAGE_RULE_NOT_IN = 'messages.errors.rules.not_in';
  const MESSAGE_RULE_MIN = 'messages.errors.rules.min';
  const MESSAGE_RULE_URL = 'messages.errors.rules.url';
  const MESSAGE_RULE_MAX = 'messages.errors.rules.max';
  const MESSAGE_RULE_INTEGER = 'messages.errors.rules.integer';
  const MESSAGE_RULE_MIMES = 'messages.errors.rules.mimes';
  const MESSAGE_RULE_EMAIL = 'messages.errors.rules.email';
  const MESSAGE_RULE_UNIQUE = 'messages.errors.rules.unique';
  const MESSAGE_RULE_JSON = 'messages.errors.rules.json';
  const MESSAGE_RULE_IMAGE = 'messages.errors.rules.image';
  const MESSAGE_RULE_ARRAY = 'messages.errors.rules.array';
  const MESSAGE_RULE_BOOLEAN = 'messages.errors.rules.boolean';
  const MESSAGE_RULE_REGEX = 'messages.errors.rules.regex';
  const MESSAGE_RULE_EXISTS = 'messages.errors.rules.exist';
  const MESSAGE_RULE_SAME = 'messages.errors.rules.same';
  const MESSAGE_RULE_NUMERIC = 'messages.errors.rules.numeric';
  public const INVALID_DATA_FOR_KEY = 'messages.errors.rules.invalid_key';
  const MESSAGE_RULE_UPLOADED = 'messages.errors.rules.uploaded';
  const MESSAGE_RULE_AFTER = 'messages.errors.rules.after';
  const MESSAGE_RULE_DATE = 'messages.errors.rules.date';
  const MESSAGE_DATE_FORMAT = 'messages.errors.rules.date_format';
}
