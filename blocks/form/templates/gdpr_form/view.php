<?php

defined('C5_EXECUTE') or die("Access Denied.");

use \Concrete\Block\Form\MiniSurvey;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;

$survey = $controller;
$miniSurvey = new MiniSurvey($b);
$miniSurvey->frontEndMode = true;

//Clean up variables from controller so html is easier to work with...
$bID = intval($bID);
$qsID = intval($survey->questionSetId);
$formAction = $view->action('submit_form').'#formblock'.$bID;

$questionsRS = $miniSurvey->loadQuestions($qsID, $bID);

$questions = [];
while ($questionRow = $questionsRS->fetch()) {
    $question = $questionRow;
    $question['input'] = $miniSurvey->loadInputType($questionRow, false);

    //Make type names common-sensical
    if ($questionRow['inputType'] == 'text') {
        $question['type'] = 'textarea';
    } elseif ($questionRow['inputType'] == 'field') {
        $question['type'] = 'text';
    } else {
        $question['type'] = $questionRow['inputType'];
    }

    $question['labelFor'] = 'for="Question' . $questionRow['msqID'] . '"';

    //Remove hardcoded style on textareas
    if ($question['type'] == 'textarea') {
        $question['input'] = str_replace('style="width:95%"', '', $question['input']);
    }

    $questions[] = $question;
}

//Prep thank-you message
$success = (\Request::request('surveySuccess') && \Request::request('qsid') == intval($qsID));
$thanksMsg = $survey->thankyouMsg;

//Collate all errors and put them into divs
$errorHeader = isset($formResponse) ? $formResponse : null;
$errors = isset($errors) && is_array($errors) ? $errors : [];
if (isset($invalidIP) && $invalidIP) {
    $errors[] = $invalidIP;
}
$errorDivs = '';
foreach ($errors as $error) {
    $errorDivs .= '<div class="error">'.$error."</div>\n"; //It's okay for this one thing to have the html here -- it can be identified in CSS via parent wrapper div (e.g. '.formblock .error')
}

//Prep captcha
$surveyBlockInfo = $miniSurvey->getMiniSurveyBlockInfoByQuestionId($qsID, $bID);
$captcha = $surveyBlockInfo['displayCaptcha'] ? Loader::helper('validation/captcha') : false;

$app = Application::getFacadeApplication();
/** @var Repository $config */
$config = $app->make(Repository::class);
$privacyPageUrl = (string)Url::to(Page::getByID($config->get("bitter_theme.privacy_page_id")));

?>

<div id="formblock<?php  echo $bID; ?>" class="ccm-block-type-form">
    <form enctype="multipart/form-data" class="form-stacked miniSurveyView" id="miniSurveyView<?php  echo $bID; ?>" method="post" action="<?php  echo $formAction ?>">
        <?=Core::make('token')->output('form_block_submit_qs_'.$qsID);?>
        <?php  if ($success): ?>

            <div class="alert alert-success">
                <?php  echo h($thanksMsg); ?>
            </div>

        <?php  elseif ($errors): ?>

            <div class="alert alert-danger">
                <?php  echo $errorHeader; ?>
                <?php  echo $errorDivs; /* each error wrapped in <div class="error">...</div> */ ?>
            </div>

        <?php  endif; ?>


        <div class="fields">

            <?php  foreach ($questions as $question): ?>
                <div class="form-group field field-<?php  echo $question['type']; ?> <?php echo isset($errorDetails[$question['msqID']]) ? 'has-error' : ''?>">
                    <label class="control-label" <?php  echo $question['labelFor']; ?>>
                        <?php  echo $question['question']; ?>
                        <?php if ($question['required']): ?>
                            <span class="text-muted small" style="font-weight: normal"><?=t("Required")?></span>
                        <?php  endif; ?>
                    </label>
                    <?php  echo $question['input']; ?>
                </div>
            <?php  endforeach; ?>

        </div><!-- .fields -->

        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="optin">
                <?php echo t("I hereby acknowledge that the data collected in this form will be stored for further use and deleted once my inquiry has been processed. See more detailed information on how we use user-data in our %s.", sprintf("<a href=\"%s\">%s</a>", $privacyPageUrl, t("privacy policy"))); ?>
            </label>
        </div>

        <?php  if ($captcha): ?>
            <div class="form-group captcha">
                <?php
                $captchaLabel = $captcha->label();
                if (!empty($captchaLabel)) {
                    ?>
                    <label class="control-label"><?php echo $captchaLabel; ?></label>
                    <?php

                }
                ?>
                <div><?php  $captcha->display(); ?></div>
                <div><?php  $captcha->showInput(); ?></div>
            </div>
        <?php  endif; ?>

        <div class="form-actions">
            <input type="submit" name="Submit" class="btn btn-primary" value="<?php  echo h(t($survey->submitText)); ?>" />
        </div>

        <input name="qsID" type="hidden" value="<?php  echo $qsID; ?>" />
        <input name="pURI" type="hidden" value="<?php  echo isset($pURI) ? $pURI : ''; ?>" />

    </form>
</div><!-- .formblock -->
