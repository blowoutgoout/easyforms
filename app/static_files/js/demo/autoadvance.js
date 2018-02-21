/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3.7
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */
$( document ).ready(function() {

    /**
     * Auto Advance (Multi-Step Form)
     * Automatically advance to the next step
     *
     * The form automatically continues with the next step
     * when the user select a radio button
     * without press the "next" button
     *
     * Useful for quizzes with radio buttons
     */

    $('input[type=radio]').on('change', nextStep);

});
