<?php
/**
 * @file
 * Contains \Drupal\user_registration\Form\RegistrationForm.
 */
namespace Drupal\user_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;  
class RegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_registration_form';
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Họ tên:'),
      '#required' => TRUE,
    );
    $form['user_pnum'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Số  điện thoại:'),
      '#required' => TRUE,
    );
    $form['user_mail'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email:'),
    //   '#required' => TRUE,
    );
    $age = array();
    for ($i = 10; $i < 100; $i++) {
        array_push($age,$i);
    }

    $form['user_age'] = array (
        '#type' => 'select',
        '#title' => ('Tuổi của bạn:'),
        '#options' => $age,
      );
    $form['user_des'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Mô tả bản thân:'),
        // '#required' => TRUE,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Register'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
      $temp = 0;
    if(str_contains($form_state->getValue('user_mail'), '@gmail.com')) {
        $temp = 1;
    }
    if($temp == 0) {
    $form_state->setErrorByName('user_mail', $this->t('Nhập email hợp lệ (abc@gmail.com)! '));
    }
    print_r((int)$form_state->getValue('user_age'));
    if((int)$form_state->getValue('user_age')+10 < 18) {
      $form_state->setErrorByName('user_age', $this->t('Bạn chưa đủ tuổi để đăng kí', ));
    }
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = [
        'name' => $form_state->getValue('user_name'),
        'phonenumber' => $form_state->getValue('user_pnum'),
        'email' => $form_state->getValue('user_mail'),
        'age' => $form_state->getValue('user_age'),
        'des' => $form_state->getValue('user_des'),
        // Add more fields as needed.
      ];
      
      $connection = Database::getConnection();
      $connection->insert('user_registration')
        ->fields($data)
        ->execute();
    \Drupal::messenger()->addMessage($this->t("user Registration Done!! Registered Values are:"));
	foreach ($form_state->getValues() as $key => $value) {
	  \Drupal::messenger()->addMessage($key . ': ' . $value);
    }
  }

}