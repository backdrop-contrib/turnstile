<?php

/**
 * Administrative settings for Turnstile.
 */
function turnstile_admin_settings() {
  $config = config('turnstile.settings');

  $form['#config'] = 'turnstile.settings';
  $form['general'] = [
    '#type' => 'details',
    '#summary' => t('General settings'),
    '#open' => TRUE,
  ];

  $form['general']['site_key'] = [
    '#default_value' => $config->get('site_key'),
    '#description' => t('The site key given to you when you <a href="@url">register for Turnstile</a>.',  array('@url' => 'https://cloudflare.com')),
    '#maxlength' => 50,
    '#required' => TRUE,
    '#title' => t('Site key'),
    '#type' => 'textfield',
  ];

  $form['general']['secret_key'] = [
    '#default_value' => $config->get('secret_key'),
    '#description' => t('The secret key given to you when you <a href="@url">register for Turnstile</a>.', array('@url' => 'https://cloudflare.com')),
    '#maxlength' => 50,
    '#required' => TRUE,
    '#title' => t('Secret key'),
    '#type' => 'textfield',
  ];

  $form['general']['src'] = [
    '#default_value' => $config->get('src'),
    '#description' => t('Default URL is "@url".', array('@url' => 'https://challenges.cloudflare.com/turnstile/v0/api.js')),
    '#maxlength' => 200,
    '#required' => TRUE,
    '#title' => t('Turnstile JavaScript resource URL'),
    '#type' => 'textfield',
  ];

  // Widget configurations.
  $form['widget'] = [
    '#type' => 'details',
    '#summary' => t('Widget settings'),
    '#open' => TRUE,
  ];
  $form['widget']['theme'] = [
    '#default_value' => $config->get('theme'),
    '#description' => t('Defines which theme to use for Turnstile.'),
    '#options' => [
      'light' => t('Light (default)'),
      'dark' => t('Dark'),
      'auto' => t('Auto'),
    ],
    '#title' => t('Theme'),
    '#type' => 'select',
  ];
  $form['widget']['size'] = array(
    '#default_value' => $config->get('size'),
    '#description' => t('The size to serve.'),
    '#options' => array(
      'normal' => t('Normal (default)'),
      'compact' => t('Compact'),
    ),
    '#title' => t('Size'),
    '#type' => 'select',
  );
  $form['widget']['tabindex'] = [
    '#default_value' => $config->get('tabindex'),
    '#description' => t('Set the <a href="@tabindex">tabindex</a> of the widget and challenge (Default = 0). If other elements on your page use tabindex, it should be set to make user navigation easier.', array('@tabindex' => 'https://www.w3.org/TR/html4/interact/forms.html#adef-tabindex')),
    '#maxlength' => 4,
    '#title' => t('Tabindex'),
    '#type' => 'number',
    '#min' => -1,
  ];

  return system_settings_form($form);
}
