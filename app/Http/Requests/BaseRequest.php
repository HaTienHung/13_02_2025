<?php

namespace App\Http\Requests;

use App\Enums\Constant;
use App\Enums\RulesConstant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use ReflectionClass;
use ReflectionException;

abstract class BaseRequest extends FormRequest
{

  public array $ruleNames = [
    RulesConstant::RULE_REQUIRED,
    RulesConstant::RULE_INTEGER,
    RulesConstant::RULE_NUMERIC,
    RulesConstant::RULE_STRING,
    RulesConstant::RULE_URL,
    RulesConstant::RULE_IN,
    RulesConstant::RULE_NOT_IN,
    RulesConstant::RULE_MIN,
    RulesConstant::RULE_MAX,
    RulesConstant::RULE_MIMES,
    RulesConstant::RULE_EMAIL,
    RulesConstant::RULE_UNIQUE,
    RulesConstant::RULE_JSON,
    RulesConstant::RULE_IMAGE,
    RulesConstant::RULE_ARRAY,
    RulesConstant::RULE_BOOLEAN,
    RulesConstant::RULE_REGEX,
    RulesConstant::RULE_EXISTS,
    RulesConstant::RULE_SAME,
    RulesConstant::RULE_AFTER,
    RulesConstant::RULE_DATE,
    RulesConstant::RULE_DATE_FORMAT,
  ];

  public array $addRules = [];

  public array $rulesToMessages = [
    RulesConstant::RULE_REQUIRED => RulesConstant::MESSAGE_RULE_REQUIRED,
    RulesConstant::RULE_INTEGER => RulesConstant::MESSAGE_RULE_INTEGER,
    RulesConstant::RULE_NUMERIC => RulesConstant::MESSAGE_RULE_NUMERIC,
    RulesConstant::RULE_STRING => RulesConstant::MESSAGE_RULE_STRING,
    RulesConstant::RULE_URL => RulesConstant::MESSAGE_RULE_URL,
    RulesConstant::RULE_IN => RulesConstant::MESSAGE_RULE_IN,
    RulesConstant::RULE_NOT_IN => RulesConstant::MESSAGE_RULE_NOT_IN,
    RulesConstant::RULE_MIN => RulesConstant::MESSAGE_RULE_MIN,
    RulesConstant::RULE_MAX => RulesConstant::MESSAGE_RULE_MAX,
    RulesConstant::RULE_MIMES => RulesConstant::MESSAGE_RULE_MIMES,
    RulesConstant::RULE_EMAIL => RulesConstant::MESSAGE_RULE_EMAIL,
    RulesConstant::RULE_UNIQUE => RulesConstant::MESSAGE_RULE_UNIQUE,
    RulesConstant::RULE_JSON => RulesConstant::MESSAGE_RULE_JSON,
    RulesConstant::RULE_IMAGE => RulesConstant::MESSAGE_RULE_IMAGE,
    RulesConstant::RULE_ARRAY => RulesConstant::MESSAGE_RULE_ARRAY,
    RulesConstant::RULE_BOOLEAN => RulesConstant::MESSAGE_RULE_BOOLEAN,
    RulesConstant::RULE_REGEX => RulesConstant::MESSAGE_RULE_REGEX,
    RulesConstant::RULE_EXISTS => RulesConstant::MESSAGE_RULE_EXISTS,
    RulesConstant::RULE_SAME => RulesConstant::MESSAGE_RULE_SAME,
    RulesConstant::RULE_AFTER => RulesConstant::MESSAGE_RULE_AFTER,
    RulesConstant::RULE_DATE => RulesConstant::MESSAGE_RULE_DATE,
    RulesConstant::RULE_DATE_FORMAT => RulesConstant::MESSAGE_DATE_FORMAT,
  ];

  public array $addRulesToMessages = [];

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  abstract function rules();

  /**
   * @return array
   * @throws ReflectionException
   */
  public function messages(): array
  {
    $messages = [];

    foreach ($this->rules() as $key => $keyRules) {

      if (is_string($keyRules)) {
        $keyRules = explode(RulesConstant::PIPELINE, $keyRules);
      }

      foreach ($keyRules as $keyRule) {
        if (is_object($keyRule)) {
          $className = (new ReflectionClass($keyRule))->getShortName();
          $messages["{$key}.{$className}"] = trans($keyRule->message(), [RulesConstant::KEY_ATTRIBUTE => $key]);
          continue;
        }
        if (in_array($keyRule, $this->getRuleNames(), true)) {
          $messages["{$key}.{$keyRule}"] = $this->getKeyMessage($keyRule, $key);
          continue;
        }
        $extract = explode(RulesConstant::COLON, $keyRule);
        if (isset($extract[1]) && in_array($extract[0], $this->getRuleNames(), true)) {
          $messages["{$key}.{$extract[0]}"] = $this->getKeyValueMessage($extract, $key);
        }
      }
    }

    return $messages;
  }

  /**
   * @return array
   */
  protected function getRuleNames(): array
  {
    return array_merge($this->ruleNames, $this->addRules);
  }

  /**
   * @param string $keyRule
   * @param string $key
   * @return string
   */
  protected function getKeyMessage(string $keyRule, string $key): string
  {
    $template = $this->getRulesToMessages()[$keyRule] ?? RulesConstant::INVALID_DATA_FOR_KEY;
    return trans($template, [RulesConstant::KEY_ATTRIBUTE => $key]);
  }

  /**
   * @param array $newRules
   * @return $this
   */
  public function addRuleNames(array $newRules): self
  {
    $this->addRules = array_merge($this->addRules, $newRules);

    return $this;
  }

  /**
   * @param array $newRules
   * @return $this
   */
  public function addRulesToMessages(array $newRules): self
  {
    $this->addRulesToMessages = array_merge($this->addRulesToMessages, $newRules);

    return $this;
  }

  /**
   * @param array $extract
   * @param string $key
   * @return string
   */
  protected function getKeyValueMessage(array $extract = [], string $key = ''): string
  {
    if ([] == $extract) {
      return 'Invalid data for ' . $key;
    }

    $template = $this->getRulesToMessages()[$extract[0]] ?? RulesConstant::INVALID_DATA_FOR_KEY;

    return trans(
      $template,
      [
        RulesConstant::KEY_ATTRIBUTE => $key,
        RulesConstant::RULE_VALUE => trim(implode(', ', explode(',', $extract[1])))
      ]
    );
  }

  /**
   * @return array
   */
  public function getRulesToMessages(): array
  {
    return array_merge($this->rulesToMessages, $this->addRulesToMessages);
  }

  /**
   * @param Validator $validator
   */
  protected function failedValidation(Validator $validator)
  {

    $errors = $validator->getMessageBag()->toArray();

    array_walk_recursive($errors, function (&$value) {
      $value = $value === RulesConstant::RULE_UPLOADED ? trans(RulesConstant::MESSAGE_RULE_UPLOADED) : $value;
    });

    throw new HttpResponseException(response()->json([
      'status' => Constant::HTTP_UNPROCESSABLE_ENTITY,
      'message' => $errors,
      'data' => []
    ], Constant::HTTP_UNPROCESSABLE_ENTITY));
  }
}
