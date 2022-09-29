<?php

namespace Drupal\turnstile\Turnstile;

use \Drupal\Core\StringTranslation\StringTranslationTrait

/**
 * Serverside validation of the Turnstile code.
 */

class Turnstile
{
    /**
     * Sets the initial URL
     */
    const SITE_VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    protected $attributes = [
    'class' => 'cf-turnstile',
    'data-sitekey' => '',
    'data-theme' => '',
    'data-size' => '',
    'data-tabindex' => 0,
    ];

    protected $siteKey = '';
    protected $secretKey = '';
    protected $errors = [];
    private $success = false;
    private $validated;
    private $requestMethod;

    public function __construct($site_key, $secret_key, $attributes = [], RequestMethod $requestMethod = null)
    {
        $this->siteKey = $site_key;
        $this->secretKey = $secret_key;
        $this->requestMethod = $requestMethod;

        if (!empty($attributes) && is_array($attributes)) {
            foreach ($attributes as $name => $attribute) {
                if (isset($this->attributes[$name])) {
                    $this->attributes[$name] = $attribute;
                }
            }
        }
    }

    /**
     * Build the Turnstile captcha form.
     *
     * @return mixed
     */
    public function getWidget($validation_function)
    {
        // Captcha requires TRUE to be returned in solution.
        $widget['solution'] = true;
        $widget['captcha_validate'] = $validation_function;
        $widget['form']['captcha_response'] = [
        '#type' => 'hidden',
        '#value' => 'Turnstile no captcha',
        ];

        // As the validate callback does not depend on sid or solution, this
        // captcha type can be displayed on cached pages.
        $widget['cacheable'] = true;

        $widget['form']['turnstile_widget'] = [
        '#markup' => '<div' . $this->getAttributesString() . '></div>',
        ];
        return $widget;
    }

    /**
     * Build the Turnstile validation mechanism.
     *
     * @return mixed
     */
    public function validate($response_token, $remote_ip = '')
    {
        $query = [
        'secret' => $this->secretKey,
        'response' => $response_token,
        'remoteip' => $remote_ip,
        ];
        $this->validated = $this->requestMethod->submit(self::SITE_VERIFY_URL, array_filter($query));

        if (isset($this->validated->success) && $this->validated->success === true) {
            // Verified!
            $this->success = true;
        } else {
            $this->errors = $this->getResponseErrors();
        }
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getResponseErrors()
    {
        // Error code reference, https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
        $errors = [];
        if (isset($this->validated->{'error-codes'}) && is_array($this->validated->{'error-codes'})) {
            $error_codes = $this->getErrorCodes();
            foreach ($this->validated->{'error-codes'} as $code) {
                if (!isset($error_codes[$code])) {
                    $code = 'unknown-error';
                }
                $errors[] = $error_codes[$code];
            }
        }
        return $errors;
    }

    /**
     * Return error codes.
     *
     * @return mixed
     */
    public function getErrorCodes()
    {
        $error_codes = [
        'missing-input-secret' => $this->t('The secret parameter was not passed.'),
        'invalid-input-secret' => $this->t('The secret parameter was invalid or did not exist.'),
        'missing-input-response' => $this->t('The response parameter was not passed.'),
        'invalid-input-response' => $this->t('The response parameter is invalid or has expired.'),
        'bad-request' => $this->t('The request was rejected because it was malformed.'),
        'timeout-or-duplicate' => $this->t('The response parameter has already been validated before.'),
        'internal-error' => $this->t('An internal error happened while validating the response. The request can be retried.'),
        ];
        return $error_codes;
    }

    /**
     * Get an attribute that's been processed.
     *
     * @return mixed
     */
    public function getAttributesString()
    {
        $attributes = array_filter($this->attributes);
        foreach ($attributes as $attribute => &$data) {
            $data = implode(' ', (array) $data);
            $data = $attribute . '="' . htmlspecialchars($data, ENT_QUOTES, 'UTF-8') . '"';
        }
        return $attributes ? ' ' . implode(' ', $attributes) : '';
    }
}