<?php

namespace Drupal\turnstile\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Configure Turnstile settings for this site.
 */
class TurnstileAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'turnstile_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['turnstile.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('turnstile.settings');

    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
    ];

    $form['general']['turnstile_site_key'] = [
      '#default_value' => $config->get('site_key'),
      '#description' => $this->t('The site key given to you when you <a href=":url">register for Turnstile</a>.', [':url' => 'https://cloudflare.com']),
      '#maxlength' => 50,
      '#required' => TRUE,
      '#title' => $this->t('Site key'),
      '#type' => 'textfield',
    ];

    $form['general']['turnstile_secret_key'] = [
      '#default_value' => $config->get('secret_key'),
      '#description' => $this->t('The secret key given to you when you <a href=":url">register for Turnstile</a>.', [':url' => 'https://cloudflare.com']),
      '#maxlength' => 50,
      '#required' => TRUE,
      '#title' => $this->t('Secret key'),
      '#type' => 'textfield',
    ];

    $form['general']['turnstile_src'] = [
      '#default_value' => $config->get('turnstile_src'),
      '#description' => $this->t('Default URL is ":url".', [':url' => 'https://challenges.cloudflare.com/turnstile/v0/api.js']),
      '#maxlength' => 200,
      '#required' => TRUE,
      '#title' => $this->t('Turnstile JavaScript resource URL'),
      '#type' => 'textfield',
    ];

    // Widget configurations.
    $form['widget'] = [
      '#type' => 'details',
      '#title' => $this->t('Widget settings'),
      '#open' => TRUE,
    ];
    $form['widget']['turnstile_theme'] = [
      '#default_value' => $config->get('widget.theme'),
      '#description' => $this->t('Defines which theme to use for Turnstile.'),
      '#options' => [
        'light' => $this->t('Light (default)'),
        'dark' => $this->t('Dark'),
        'auto' => $this->t('Auto'),
      ],
      '#title' => $this->t('Theme'),
      '#type' => 'select',
    ];

    $form['widget']['turnstile_tabindex'] = [
      '#default_value' => $config->get('widget.tabindex'),
      '#description' => $this->t('Set the <a href=":tabindex">tabindex</a> of the widget and challenge (Default = 0). If other elements in your page use tabindex, it should be set to make user navigation easier.', [':tabindex' => Url::fromUri('https://www.w3.org/TR/html4/interact/forms.html', ['fragment' => 'adef-tabindex'])->toString()]),
      '#maxlength' => 4,
      '#title' => $this->t('Tabindex'),
      '#type' => 'number',
      '#min' => -1,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('turnstile.settings');
    $config
      ->set('site_key', $form_state->getValue('turnstile_site_key'))
      ->set('secret_key', $form_state->getValue('turnstile_secret_key'))
      ->set('turnstile_src', $form_state->getValue('turnstile_src'))
      ->set('widget.theme', $form_state->getValue('turnstile_theme'))
      ->set('widget.tabindex', $form_state->getValue('turnstile_tabindex'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
