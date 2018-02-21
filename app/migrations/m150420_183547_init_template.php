<?php

use yii\db\Schema;
use yii\db\Migration;

class m150420_183547_init_template extends Migration
{

    private $fifteenMinutesAgo;

    public function init()
    {

        $this->fifteenMinutesAgo = strtotime('-15 minutes');

        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%template_category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255)',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        // insert category data
        $columns = ['name', 'description', 'created_at', 'updated_at'];
        $this->batchInsert('{{%template_category}}', $columns, [
            ['Online Forms', 'If you need a ready-to-go form for your website, you\'ve come to the right place.', time(), time()],
            ['Surveys', 'Curious what people think? Need to do some polling? Then this online surveys are made for you.', time(), time()],
            ['Lead Generation', 'A lead generation template is a critical piece of the puzzle on any website designed to attract customer inquiries for follow up.', $this->fifteenMinutesAgo, $this->fifteenMinutesAgo],
            ['Invitation', 'Party? Did someone say party? Add a online invitation to your website, or send it out through email, to make collecting responses a snap.', $this->fifteenMinutesAgo, $this->fifteenMinutesAgo],
            ['Online Order', 'Are you looking for a way to take orders online instead of over the phone? Well then, what you need is an online order form template.', $this->fifteenMinutesAgo, $this->fifteenMinutesAgo],
            ['Registrations', 'Are you an event planner, or has someone "volunteered" you to organize that ski lodge reservation for all of your friends? This templates will help you to make organizing events a painless process.', $this->fifteenMinutesAgo, $this->fifteenMinutesAgo],
            ['Tracking', 'Spreadsheets are so yesterday for inventory and tracking purposes. Instead of a spreadsheet, you need a tracking form to keep tabs on inventory, host evaluations, file addresses, or even to record your exercise habits.', $this->fifteenMinutesAgo, $this->fifteenMinutesAgo],
        ]);

        $this->createTable('{{%template}}', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . '(11)',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_STRING . '(255)',
            'builder' => 'mediumtext', // MySql type
            'html' => 'mediumtext', // MySql type
            'promoted' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE',
            'slug' => Schema::TYPE_STRING . '(255)',
            'created_by' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_by' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->insert('{{%template}}', [
            'id' => 1,
            'category_id' => 1,
            'name' => 'Basic Contact Form',
            'description' => 'Contact information is important for business owners, professionals, and other organizations. This form allows you to collect name, email addresses and other information so that you can reach personal or business contacts in the future.',
            'builder' => '{"settings":{"name":"Contact Form","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Contact Us","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"Let us know your questions, suggestions and concerns by filling out the contact form below.","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Email","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Message","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":412}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Contact Us&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;Let us know your questions, suggestions and concerns by filling out the contact form below.&lt;/p&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Email&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Message&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 1,
            'slug' => 'basic-contact-form',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => time(),
        ]);

        $this->insert('{{%template}}', [
            'id' => 2,
            'category_id' => 2,
            'name' => 'Customer Satisfaction Survey',
            'description' => 'You don\'t need an expensive marketing research team to gather detailed information about your customers. Instead, use this survey for a quick and easy way to get invaluable feedback from customers on the quality of your product or service.',
            'builder' => '{"settings":{"name":"Customer Satisfaction Survey","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Customer Satisfaction Survey","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"Please take a few moments to complete this satisfaction survey.","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Overall, how satisfied were you with the product \/ service?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Very Satisfied","Satisfied","Neutral","Unsatisfied","Very Unsatisfied","N\/A"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Would you recommend our product \/ service to colleagues or contacts within your industry?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Definitely","Probably","Not Sure","Probably Not","Definitely Not"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_2","name":"id"},"label":{"label":"component.label","type":"input","value":"Would you use our product \/ service in the future?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Less than a month","1-6 months","1-3 years","Over 3 Years","Never used"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_3","name":"id"},"label":{"label":"component.label","type":"input","value":"How often do you use product \/ service?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Once a week","2 to 3 times a month","Once a month","Less than once a month"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_4","name":"id"},"label":{"label":"component.label","type":"input","value":"What aspect of the product \/ service were you most satisfied by?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Quality","Price","Purchase Experience","Installation or First Use Experience","Usage Experience","Customer Service","Repeat Purchase Experience"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"What do you like about the product \/ service?","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_1","name":"id"},"label":{"label":"component.label","type":"input","value":"What do you dislike about the product \/ service?","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_5","name":"id"},"label":{"label":"component.label","type":"input","value":"Thinking of similar products \/ services offered by other companies, how would you compare the product \/ service offered by our company?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Much Better","Somewhat Better","About the Same","Somewhat Worse","Much Worse","Don\u0027t Know"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":1526}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Customer Satisfaction Survey&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;Please take a few moments to complete this satisfaction survey.&lt;/p&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;Overall, how satisfied were you with the product / service?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;Very Satisfied&quot;&gt; Very Satisfied &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;Satisfied&quot;&gt; Satisfied &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_2&quot; value=&quot;Neutral&quot;&gt; Neutral &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_3&quot; value=&quot;Unsatisfied&quot;&gt; Unsatisfied &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_4&quot; value=&quot;Very Unsatisfied&quot;&gt; Very Unsatisfied &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_5&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_5&quot; value=&quot;N/A&quot;&gt; N/A &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_1&quot;&gt;Would you recommend our product / service to colleagues or contacts within your industry?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_0&quot; value=&quot;Definitely&quot;&gt; Definitely &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_1&quot; value=&quot;Probably&quot;&gt; Probably &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_2&quot; value=&quot;Not Sure&quot;&gt; Not Sure &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_3&quot; value=&quot;Probably Not&quot;&gt; Probably Not &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_4&quot; value=&quot;Definitely Not&quot;&gt; Definitely Not &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_1&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_2&quot;&gt;Would you use our product / service in the future?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_0&quot; value=&quot;Less than a month&quot;&gt; Less than a month &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_1&quot; value=&quot;1-6 months&quot;&gt; 1-6 months &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_2&quot; value=&quot;1-3 years&quot;&gt; 1-3 years &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_3&quot; value=&quot;Over 3 Years&quot;&gt; Over 3 Years &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_4&quot; value=&quot;Never used&quot;&gt; Never used &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_2&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_3&quot;&gt;How often do you use product / service?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_0&quot; value=&quot;Once a week&quot;&gt; Once a week &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_1&quot; value=&quot;2 to 3 times a month&quot;&gt; 2 to 3 times a month &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_2&quot; value=&quot;Once a month&quot;&gt; Once a month &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_3&quot; value=&quot;Less than once a month&quot;&gt; Less than once a month &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_3&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_4&quot;&gt;What aspect of the product / service were you most satisfied by?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_0&quot; value=&quot;Quality&quot;&gt; Quality &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_1&quot; value=&quot;Price&quot;&gt; Price &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_2&quot; value=&quot;Purchase Experience&quot;&gt; Purchase Experience &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_3&quot; value=&quot;Installation or First Use Experience&quot;&gt; Installation or First Use Experience &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_4&quot; value=&quot;Usage Experience&quot;&gt; Usage Experience &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_5&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_5&quot; value=&quot;Customer Service&quot;&gt; Customer Service &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_4_6&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_4&quot; id=&quot;radio_4_6&quot; value=&quot;Repeat Purchase Experience&quot;&gt; Repeat Purchase Experience &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_4&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;What do you like about the product / service?&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_1&quot;&gt;What do you dislike about the product / service?&lt;/label&gt;
    &lt;textarea id=&quot;textarea_1&quot; name=&quot;textarea_1&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_5&quot;&gt;Thinking of similar products / services offered by other companies, how would you compare the product / service offered by our company?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_0&quot; value=&quot;Much Better&quot;&gt; Much Better &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_1&quot; value=&quot;Somewhat Better&quot;&gt; Somewhat Better &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_2&quot; value=&quot;About the Same&quot;&gt; About the Same &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_3&quot; value=&quot;Somewhat Worse&quot;&gt; Somewhat Worse &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_4&quot; value=&quot;Much Worse&quot;&gt; Much Worse &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_5_5&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_5&quot; id=&quot;radio_5_5&quot; value=&quot;Don&#039;t Know&quot;&gt; Don&#039;t Know &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_5&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 1,
            'slug' => 'customer-satisfaction-survey',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 3,
            'category_id' => 1,
            'name' => 'Job Application Form',
            'description' => 'Easy way to apply online. Gather information and upload resume using the form.',
            'builder' => '{"settings":{"name":"Job Application Form","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Product Manager","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Cstrong\u003EWill you be our next Product Manager?\u003C\/strong\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"snippet","title":"snippet.title","fields":{"id":{"label":"component.id","type":"input","value":"snippet_0","name":"id"},"snippet":{"label":"component.htmlCode","type":"textarea","value":"\u003Cdiv style=\u0022border:1px solid #DDD;padding:10px;overflow-y: scroll;height: 200px;margin-bottom:20px\u0022\u003E\n    \u003Cp\u003E\u003Cstrong\u003EAre you...\u003C\/strong\u003E\u003C\/p\u003E\n    \u003Cul\u003E\n        \u003Cli\u003EFascinated by software products and how they can impact the lives of their users?\u003C\/li\u003E\n        \u003Cli\u003ESomeone who enjoys collaborating with customers, developers and marketers to develop a roadmap for a product?\u003C\/li\u003E\n        \u003Cli\u003EA person that enjoys being a vital part of an organization?\u003C\/li\u003E\n        \u003Cli\u003EA believer that great software can provide significant savings of time and money?\u003C\/li\u003E\n    \u003C\/ul\u003E\n    \u003Cp\u003EIf so, you should consider applying to become our next Product Manager.\u003C\/p\u003E\n    \u003Cp\u003EWe are looking for a high energy and fun person to add to our team.\u003C\/p\u003E\n    \u003Cp\u003EThey will have the opportunity to significantly direct and impact the development path of our web application and the future of our product.\u003C\/p\u003E\n    \u003Cp\u003E\u003Cstrong\u003EResponsibilities:\u003C\/strong\u003E\u003C\/p\u003E\n    \u003Cul\u003E\n        \u003Cli\u003EDefining new improvements for our web application, gathering requirements \u0026 documenting designs\u003C\/li\u003E\n        \u003Cli\u003ECollect and interpret customer feedback and needs but realize the customer might not always know what they need\u003C\/li\u003E\n        \u003Cli\u003EUtilize a healthy amount of intuition but balance that with the appropriate amount of \u0027data\u0027 to back your decisions\u003C\/li\u003E\n        \u003Cli\u003EAbility to prioritize\u003C\/li\u003E\n        \u003Cli\u003EManage usability testing of new features to understand the \u0022how\u0022 and \u0022why\u0022 for people who use the software\u003C\/li\u003E\n        \u003Cli\u003EProject management, seeing all projects from start to finish, new software every week\u003C\/li\u003E\n        \u003Cli\u003ECollaborate with Marketing to define the strategies and define use cases\u003C\/li\u003E\n        \u003Cli\u003EWork with the Customer Experience Team to analyze customer feedback and feature requests\u003C\/li\u003E\n        \u003Cli\u003EInsure the end product is meeting the goals set in the beginning\u003C\/li\u003E\n    \u003C\/ul\u003E\n    \u003Cp\u003E\u003Cstrong\u003ESkills:\u003C\/strong\u003E\u003C\/p\u003E\n    \u003Cul\u003E\n        \u003Cli\u003EKnowledge of Web Applications, SaaS companies and more than 4 years of experience guiding product development\u003C\/li\u003E\n        \u003Cli\u003EA background or passion for user experience and design\u003C\/li\u003E\n        \u003Cli\u003ETechnical understanding of the limitations and possibilities within the Web Applications space\u003C\/li\u003E\n        \u003Cli\u003EHighly organized with demonstrated effective verbal and written communication skills\u003C\/li\u003E\n        \u003Cli\u003EAbility to act as a liaison between departments and maintain lines of communication\u003C\/li\u003E\n        \u003Cli\u003EGoal-oriented but able to make changes and pivots when necessary\u003C\/li\u003E\n    \u003C\/ul\u003E\n    \u003Cp\u003E*we are an Indianapolis-based company but accept applications for remote team members\u003C\/p\u003E\n\u003C\/div\u003E","name":"snippet"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Last Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Email","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Address","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"How did you find out about this position?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Current Employee","Career Fair","Newspaper Ad","Radio\/TV Ad","Search Engine","Other"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_3","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"If other, please specify","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"file","title":"file.title","fields":{"id":{"label":"component.id","type":"input","value":"file_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Upload your resume","name":"label"},"accept":{"label":"component.accept","type":"input","value":".pdf, .docx, .doc","name":"accept"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"minSize":{"label":"component.minSize","type":"input","value":"","advanced":true,"name":"minSize"},"maxSize":{"label":"component.maxSize","type":"input","value":"","advanced":true,"name":"maxSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"file","title":"file.title","fields":{"id":{"label":"component.id","type":"input","value":"file_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Upload a cover letter","name":"label"},"accept":{"label":"component.accept","type":"input","value":".pdf, .docx, .doc","name":"accept"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"minSize":{"label":"component.minSize","type":"input","value":"","advanced":true,"name":"minSize"},"maxSize":{"label":"component.maxSize","type":"input","value":"","advanced":true,"name":"maxSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Additional info","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":1175}',
            'html' => '&lt;form id=&quot;form-app&quot; enctype=&quot;multipart/form-data&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Product Manager&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;strong&gt;Will you be our next Product Manager?&lt;/strong&gt;&lt;/p&gt;

&lt;!-- Snippet --&gt;
&lt;div class=&quot;snippet&quot;&gt;&lt;div style=&quot;border:1px solid #DDD;padding:10px;overflow-y: scroll;height: 200px;margin-bottom:20px&quot;&gt;
    &lt;p&gt;&lt;strong&gt;Are you...&lt;/strong&gt;&lt;/p&gt;
    &lt;ul&gt;
        &lt;li&gt;Fascinated by software products and how they can impact the lives of their users?&lt;/li&gt;
        &lt;li&gt;Someone who enjoys collaborating with customers, developers and marketers to develop a roadmap for a product?&lt;/li&gt;
        &lt;li&gt;A person that enjoys being a vital part of an organization?&lt;/li&gt;
        &lt;li&gt;A believer that great software can provide significant savings of time and money?&lt;/li&gt;
    &lt;/ul&gt;
    &lt;p&gt;If so, you should consider applying to become our next Product Manager.&lt;/p&gt;
    &lt;p&gt;We are looking for a high energy and fun person to add to our team.&lt;/p&gt;
    &lt;p&gt;They will have the opportunity to significantly direct and impact the development path of our web application and the future of our product.&lt;/p&gt;
    &lt;p&gt;&lt;strong&gt;Responsibilities:&lt;/strong&gt;&lt;/p&gt;
    &lt;ul&gt;
        &lt;li&gt;Defining new improvements for our web application, gathering requirements &amp;amp; documenting designs&lt;/li&gt;
        &lt;li&gt;Collect and interpret customer feedback and needs but realize the customer might not always know what they need&lt;/li&gt;
        &lt;li&gt;Utilize a healthy amount of intuition but balance that with the appropriate amount of &#039;data&#039; to back your decisions&lt;/li&gt;
        &lt;li&gt;Ability to prioritize&lt;/li&gt;
        &lt;li&gt;Manage usability testing of new features to understand the &quot;how&quot; and &quot;why&quot; for people who use the software&lt;/li&gt;
        &lt;li&gt;Project management, seeing all projects from start to finish, new software every week&lt;/li&gt;
        &lt;li&gt;Collaborate with Marketing to define the strategies and define use cases&lt;/li&gt;
        &lt;li&gt;Work with the Customer Experience Team to analyze customer feedback and feature requests&lt;/li&gt;
        &lt;li&gt;Insure the end product is meeting the goals set in the beginning&lt;/li&gt;
    &lt;/ul&gt;
    &lt;p&gt;&lt;strong&gt;Skills:&lt;/strong&gt;&lt;/p&gt;
    &lt;ul&gt;
        &lt;li&gt;Knowledge of Web Applications, SaaS companies and more than 4 years of experience guiding product development&lt;/li&gt;
        &lt;li&gt;A background or passion for user experience and design&lt;/li&gt;
        &lt;li&gt;Technical understanding of the limitations and possibilities within the Web Applications space&lt;/li&gt;
        &lt;li&gt;Highly organized with demonstrated effective verbal and written communication skills&lt;/li&gt;
        &lt;li&gt;Ability to act as a liaison between departments and maintain lines of communication&lt;/li&gt;
        &lt;li&gt;Goal-oriented but able to make changes and pivots when necessary&lt;/li&gt;
    &lt;/ul&gt;
    &lt;p&gt;*we are an Indianapolis-based company but accept applications for remote team members&lt;/p&gt;
&lt;/div&gt;&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;Last Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Email&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;Address&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;How did you find out about this position?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;Current Employee&quot;&gt; Current Employee &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;Career Fair&quot;&gt; Career Fair &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_2&quot; value=&quot;Newspaper Ad&quot;&gt; Newspaper Ad &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_3&quot; value=&quot;Radio/TV Ad&quot;&gt; Radio/TV Ad &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_4&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_4&quot; value=&quot;Search Engine&quot;&gt; Search Engine &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_5&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_5&quot; value=&quot;Other&quot;&gt; Other &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_3&quot;&gt;If other, please specify&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_3&quot; name=&quot;text_3&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- File --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;file_0&quot;&gt;Upload your resume&lt;/label&gt;
    &lt;input type=&quot;file&quot; id=&quot;file_0&quot; name=&quot;file_0&quot; accept=&quot;.pdf, .docx, .doc&quot;&gt;
&lt;/div&gt;

&lt;!-- File --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;file_1&quot;&gt;Upload a cover letter&lt;/label&gt;
    &lt;input type=&quot;file&quot; id=&quot;file_1&quot; name=&quot;file_1&quot; accept=&quot;.pdf, .docx, .doc&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Additional info&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 1,
            'slug' => 'job-application-form',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 4,
            'category_id' => 7,
            'name' => 'Bug Tracker',
            'description' => 'Do you need to track bugs for an IT department or a group of developers? With this form, you can collect and track the information you need to quickly and effectively intake clients. ',
            'builder' => '{"settings":{"name":"Bug Tracker","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Bug Tracker","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"Report all bugs!","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Bug Title","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Issue Description","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Operating System","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Windows XP","Windows Vista","Mac OS X","Linux","Other"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Browser","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Internet Explorer","Chrome","Firefox","Safari","Opera","Other"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_3","name":"id"},"label":{"label":"component.label","type":"input","value":"Assign To","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Team Member #1","Team Member #2","Team Member #3"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-9 no-padding-left","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_8","name":"id"},"label":{"label":"component.label","type":"input","value":"Priority","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Low","Medium","High"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-3 no-padding","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"file","title":"file.title","fields":{"id":{"label":"component.id","type":"input","value":"file_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Upload a Screenshot","name":"label"},"accept":{"label":"component.accept","type":"input","value":".gif, .jpg, .png","name":"accept"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"minSize":{"label":"component.minSize","type":"input","value":"","advanced":true,"name":"minSize"},"maxSize":{"label":"component.maxSize","type":"input","value":"","advanced":true,"name":"maxSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_9","name":"id"},"label":{"label":"component.label","type":"input","value":"Status","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["New","Acknowledged","Confirmed","Assigned","Resolved","Closed"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":600}',
            'html' => '&lt;form id=&quot;form-app&quot; enctype=&quot;multipart/form-data&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Bug Tracker&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;Report all bugs!&lt;/p&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Bug Title&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Issue Description&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Operating System&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Windows XP&quot;&gt;Windows XP&lt;/option&gt;
        &lt;option value=&quot;Windows Vista&quot;&gt;Windows Vista&lt;/option&gt;
        &lt;option value=&quot;Mac OS X&quot;&gt;Mac OS X&lt;/option&gt;
        &lt;option value=&quot;Linux&quot;&gt;Linux&lt;/option&gt;
        &lt;option value=&quot;Other&quot;&gt;Other&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_1&quot;&gt;Browser&lt;/label&gt;
    &lt;select id=&quot;selectlist_1&quot; name=&quot;selectlist_1[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Internet Explorer&quot;&gt;Internet Explorer&lt;/option&gt;
        &lt;option value=&quot;Chrome&quot;&gt;Chrome&lt;/option&gt;
        &lt;option value=&quot;Firefox&quot;&gt;Firefox&lt;/option&gt;
        &lt;option value=&quot;Safari&quot;&gt;Safari&lt;/option&gt;
        &lt;option value=&quot;Opera&quot;&gt;Opera&lt;/option&gt;
        &lt;option value=&quot;Other&quot;&gt;Other&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control col-sm-9 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_3&quot;&gt;Assign To&lt;/label&gt;
    &lt;select id=&quot;selectlist_3&quot; name=&quot;selectlist_3[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Team Member #1&quot;&gt;Team Member #1&lt;/option&gt;
        &lt;option value=&quot;Team Member #2&quot;&gt;Team Member #2&lt;/option&gt;
        &lt;option value=&quot;Team Member #3&quot;&gt;Team Member #3&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control col-sm-3 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_8&quot;&gt;Priority&lt;/label&gt;
    &lt;select id=&quot;selectlist_8&quot; name=&quot;selectlist_8[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Low&quot;&gt;Low&lt;/option&gt;
        &lt;option value=&quot;Medium&quot;&gt;Medium&lt;/option&gt;
        &lt;option value=&quot;High&quot;&gt;High&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- File --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;file_0&quot;&gt;Upload a Screenshot&lt;/label&gt;
    &lt;input type=&quot;file&quot; id=&quot;file_0&quot; name=&quot;file_0&quot; accept=&quot;.gif, .jpg, .png&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_9&quot;&gt;Status&lt;/label&gt;
    &lt;select id=&quot;selectlist_9&quot; name=&quot;selectlist_9[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;New&quot;&gt;New&lt;/option&gt;
        &lt;option value=&quot;Acknowledged&quot;&gt;Acknowledged&lt;/option&gt;
        &lt;option value=&quot;Confirmed&quot;&gt;Confirmed&lt;/option&gt;
        &lt;option value=&quot;Assigned&quot;&gt;Assigned&lt;/option&gt;
        &lt;option value=&quot;Resolved&quot;&gt;Resolved&lt;/option&gt;
        &lt;option value=&quot;Closed&quot;&gt;Closed&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'bug-tracker',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 5,
            'category_id' => 6,
            'name' => 'Event Planner',
            'description' => 'Be it work or play, it is best planned well in advance. Here is an online form to bookmark upcoming events and to verify the checklist of to-do\'s. With this, you can be sure not to have left behind anything that adds to the fun.',
            'builder' => '{"settings":{"name":"Event Planner","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Event Planner","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Event Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Event Coordinator","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Email","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Event Type","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Banquet","Dinner Party","Wedding"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Status","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Planning","In Progress","Finished"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Event Description","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Does your program involve any type of outside activity?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Yes","No"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"number","title":"number.title","fields":{"id":{"label":"component.id","type":"input","value":"number_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"number","selected":true,"label":"Number"},{"value":"range","selected":false,"label":"Range"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Cost Per Person ($)","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"integerOnly":{"label":"component.integerOnly","type":"checkbox","value":false,"name":"integerOnly"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"min":{"label":"component.minNumber","type":"input","value":"","advanced":true,"name":"min"},"max":{"label":"component.maxNumber","type":"input","value":"","advanced":true,"name":"max"},"step":{"label":"component.stepNumber","type":"input","value":"","advanced":true,"name":"step"},"integerPattern":{"label":"component.integerPattern","type":"input","value":"","advanced":true,"name":"integerPattern"},"numberPattern":{"label":"component.numberPattern","type":"input","value":"","advanced":true,"name":"numberPattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"checkbox","title":"checkbox.title","fields":{"id":{"label":"component.groupName","type":"input","value":"checkbox_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Requirements","name":"label"},"checkboxes":{"label":"component.checkboxes","type":"textarea-split","value":["Staffing","Catering","Security"],"name":"checkboxes"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"checkbox-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"date","title":"date.title","fields":{"id":{"label":"component.id","type":"input","value":"date_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"date","selected":false,"label":"Date"},{"value":"datetime-local","selected":true,"label":"DateTime-Local"},{"value":"time","selected":false,"label":"Time"},{"value":"month","selected":false,"label":"Month"},{"value":"week","selected":false,"label":"Week"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Event Start Date","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"min":{"label":"component.minDate","type":"input","value":"","advanced":true,"name":"min"},"max":{"label":"component.maxDate","type":"input","value":"","advanced":true,"name":"max"},"step":{"label":"component.stepNumber","type":"input","value":"","advanced":true,"name":"step"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"date","title":"date.title","fields":{"id":{"label":"component.id","type":"input","value":"date_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"date","selected":false,"label":"Date"},{"value":"datetime-local","selected":true,"label":"DateTime-Local"},{"value":"time","selected":false,"label":"Time"},{"value":"month","selected":false,"label":"Month"},{"value":"week","selected":false,"label":"Week"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Event End Date","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"min":{"label":"component.minDate","type":"input","value":"","advanced":true,"name":"min"},"max":{"label":"component.maxDate","type":"input","value":"","advanced":true,"name":"max"},"step":{"label":"component.stepNumber","type":"input","value":"","advanced":true,"name":"step"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Location","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"City","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_3","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"State","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_2","name":"id"},"label":{"label":"component.label","type":"input","value":"Country","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["United States","United Kingdom","Australia","Canada","France","----","Afghanistan","Albania","Algeria","Andorra","Angola","Antigua & Deps","Argentina","Armenia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina","Burundi","Cambodia","Cameroon","Cape Verde","Central African Rep","Chad","Chile","China","Colombia","Comoros","Congo","Congo {Democratic Rep}","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland {Republic}","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea North","Korea South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar, {Burma}","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russian Federation","Rwanda","St Kitts & Nevis","St Lucia","Saint Vincent & the Grenadines","Samoa","San Marino","Sao Tome & Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-right","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"file","title":"file.title","fields":{"id":{"label":"component.id","type":"input","value":"file_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Attach Detailed Itinerary","name":"label"},"accept":{"label":"component.accept","type":"input","value":".txt, .pdf, .doc, .docx","name":"accept"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"minSize":{"label":"component.minSize","type":"input","value":"","advanced":true,"name":"minSize"},"maxSize":{"label":"component.maxSize","type":"input","value":"","advanced":true,"name":"maxSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":1126}',
            'html' => '&lt;form id=&quot;form-app&quot; enctype=&quot;multipart/form-data&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Event Planner&lt;/h3&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Event Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;Event Coordinator&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Email&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Event Type&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Banquet&quot;&gt;Banquet&lt;/option&gt;
        &lt;option value=&quot;Dinner Party&quot;&gt;Dinner Party&lt;/option&gt;
        &lt;option value=&quot;Wedding&quot;&gt;Wedding&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_1&quot;&gt;Status&lt;/label&gt;
    &lt;select id=&quot;selectlist_1&quot; name=&quot;selectlist_1[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Planning&quot;&gt;Planning&lt;/option&gt;
        &lt;option value=&quot;In Progress&quot;&gt;In Progress&lt;/option&gt;
        &lt;option value=&quot;Finished&quot;&gt;Finished&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Event Description&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;Does your program involve any type of outside activity?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;Yes&quot;&gt; Yes &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;No&quot;&gt; No &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Number --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;number_0&quot;&gt;Cost Per Person ($)&lt;/label&gt;
    &lt;input type=&quot;number&quot; id=&quot;number_0&quot; name=&quot;number_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Checkbox --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;checkbox_0&quot;&gt;Requirements&lt;/label&gt;
        &lt;div class=&quot;checkbox&quot;&gt;
            &lt;label for=&quot;checkbox_0_0&quot; class=&quot;checkbox-inline&quot;&gt;
            &lt;input type=&quot;checkbox&quot; name=&quot;checkbox_0[]&quot; id=&quot;checkbox_0_0&quot; value=&quot;Staffing&quot;&gt; Staffing &lt;/label&gt;
        &lt;/div&gt;
        &lt;div class=&quot;checkbox&quot;&gt;
            &lt;label for=&quot;checkbox_0_1&quot; class=&quot;checkbox-inline&quot;&gt;
            &lt;input type=&quot;checkbox&quot; name=&quot;checkbox_0[]&quot; id=&quot;checkbox_0_1&quot; value=&quot;Catering&quot;&gt; Catering &lt;/label&gt;
        &lt;/div&gt;
        &lt;div class=&quot;checkbox&quot;&gt;
            &lt;label for=&quot;checkbox_0_2&quot; class=&quot;checkbox-inline&quot;&gt;
            &lt;input type=&quot;checkbox&quot; name=&quot;checkbox_0[]&quot; id=&quot;checkbox_0_2&quot; value=&quot;Security&quot;&gt; Security &lt;/label&gt;
        &lt;/div&gt;
    &lt;span id=&quot;checkbox_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Date --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;date_0&quot;&gt;Event Start Date&lt;/label&gt;
    &lt;input type=&quot;datetime-local&quot; id=&quot;date_0&quot; name=&quot;date_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Date --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;date_1&quot;&gt;Event End Date&lt;/label&gt;
    &lt;input type=&quot;datetime-local&quot; id=&quot;date_1&quot; name=&quot;date_1&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_1&quot;&gt;Location&lt;/label&gt;
    &lt;textarea id=&quot;textarea_1&quot; name=&quot;textarea_1&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;City&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_3&quot;&gt;State&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_3&quot; name=&quot;text_3&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-right&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_2&quot;&gt;Country&lt;/label&gt;
    &lt;select id=&quot;selectlist_2&quot; name=&quot;selectlist_2[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
        &lt;option value=&quot;United Kingdom&quot;&gt;United Kingdom&lt;/option&gt;
        &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
        &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
        &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
        &lt;option value=&quot;----&quot;&gt;----&lt;/option&gt;
        &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
        &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
        &lt;option value=&quot;Algeria&quot;&gt;Algeria&lt;/option&gt;
        &lt;option value=&quot;Andorra&quot;&gt;Andorra&lt;/option&gt;
        &lt;option value=&quot;Angola&quot;&gt;Angola&lt;/option&gt;
        &lt;option value=&quot;Antigua &amp;amp; Deps&quot;&gt;Antigua &amp;amp; Deps&lt;/option&gt;
        &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
        &lt;option value=&quot;Armenia&quot;&gt;Armenia&lt;/option&gt;
        &lt;option value=&quot;Austria&quot;&gt;Austria&lt;/option&gt;
        &lt;option value=&quot;Azerbaijan&quot;&gt;Azerbaijan&lt;/option&gt;
        &lt;option value=&quot;Bahamas&quot;&gt;Bahamas&lt;/option&gt;
        &lt;option value=&quot;Bahrain&quot;&gt;Bahrain&lt;/option&gt;
        &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
        &lt;option value=&quot;Barbados&quot;&gt;Barbados&lt;/option&gt;
        &lt;option value=&quot;Belarus&quot;&gt;Belarus&lt;/option&gt;
        &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
        &lt;option value=&quot;Belize&quot;&gt;Belize&lt;/option&gt;
        &lt;option value=&quot;Benin&quot;&gt;Benin&lt;/option&gt;
        &lt;option value=&quot;Bhutan&quot;&gt;Bhutan&lt;/option&gt;
        &lt;option value=&quot;Bolivia&quot;&gt;Bolivia&lt;/option&gt;
        &lt;option value=&quot;Bosnia Herzegovina&quot;&gt;Bosnia Herzegovina&lt;/option&gt;
        &lt;option value=&quot;Botswana&quot;&gt;Botswana&lt;/option&gt;
        &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
        &lt;option value=&quot;Brunei&quot;&gt;Brunei&lt;/option&gt;
        &lt;option value=&quot;Bulgaria&quot;&gt;Bulgaria&lt;/option&gt;
        &lt;option value=&quot;Burkina&quot;&gt;Burkina&lt;/option&gt;
        &lt;option value=&quot;Burundi&quot;&gt;Burundi&lt;/option&gt;
        &lt;option value=&quot;Cambodia&quot;&gt;Cambodia&lt;/option&gt;
        &lt;option value=&quot;Cameroon&quot;&gt;Cameroon&lt;/option&gt;
        &lt;option value=&quot;Cape Verde&quot;&gt;Cape Verde&lt;/option&gt;
        &lt;option value=&quot;Central African Rep&quot;&gt;Central African Rep&lt;/option&gt;
        &lt;option value=&quot;Chad&quot;&gt;Chad&lt;/option&gt;
        &lt;option value=&quot;Chile&quot;&gt;Chile&lt;/option&gt;
        &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
        &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
        &lt;option value=&quot;Comoros&quot;&gt;Comoros&lt;/option&gt;
        &lt;option value=&quot;Congo&quot;&gt;Congo&lt;/option&gt;
        &lt;option value=&quot;Congo {Democratic Rep}&quot;&gt;Congo {Democratic Rep}&lt;/option&gt;
        &lt;option value=&quot;Costa Rica&quot;&gt;Costa Rica&lt;/option&gt;
        &lt;option value=&quot;Croatia&quot;&gt;Croatia&lt;/option&gt;
        &lt;option value=&quot;Cuba&quot;&gt;Cuba&lt;/option&gt;
        &lt;option value=&quot;Cyprus&quot;&gt;Cyprus&lt;/option&gt;
        &lt;option value=&quot;Czech Republic&quot;&gt;Czech Republic&lt;/option&gt;
        &lt;option value=&quot;Denmark&quot;&gt;Denmark&lt;/option&gt;
        &lt;option value=&quot;Djibouti&quot;&gt;Djibouti&lt;/option&gt;
        &lt;option value=&quot;Dominica&quot;&gt;Dominica&lt;/option&gt;
        &lt;option value=&quot;Dominican Republic&quot;&gt;Dominican Republic&lt;/option&gt;
        &lt;option value=&quot;East Timor&quot;&gt;East Timor&lt;/option&gt;
        &lt;option value=&quot;Ecuador&quot;&gt;Ecuador&lt;/option&gt;
        &lt;option value=&quot;Egypt&quot;&gt;Egypt&lt;/option&gt;
        &lt;option value=&quot;El Salvador&quot;&gt;El Salvador&lt;/option&gt;
        &lt;option value=&quot;Equatorial Guinea&quot;&gt;Equatorial Guinea&lt;/option&gt;
        &lt;option value=&quot;Eritrea&quot;&gt;Eritrea&lt;/option&gt;
        &lt;option value=&quot;Estonia&quot;&gt;Estonia&lt;/option&gt;
        &lt;option value=&quot;Ethiopia&quot;&gt;Ethiopia&lt;/option&gt;
        &lt;option value=&quot;Fiji&quot;&gt;Fiji&lt;/option&gt;
        &lt;option value=&quot;Finland&quot;&gt;Finland&lt;/option&gt;
        &lt;option value=&quot;Gabon&quot;&gt;Gabon&lt;/option&gt;
        &lt;option value=&quot;Gambia&quot;&gt;Gambia&lt;/option&gt;
        &lt;option value=&quot;Georgia&quot;&gt;Georgia&lt;/option&gt;
        &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
        &lt;option value=&quot;Ghana&quot;&gt;Ghana&lt;/option&gt;
        &lt;option value=&quot;Greece&quot;&gt;Greece&lt;/option&gt;
        &lt;option value=&quot;Grenada&quot;&gt;Grenada&lt;/option&gt;
        &lt;option value=&quot;Guatemala&quot;&gt;Guatemala&lt;/option&gt;
        &lt;option value=&quot;Guinea&quot;&gt;Guinea&lt;/option&gt;
        &lt;option value=&quot;Guinea-Bissau&quot;&gt;Guinea-Bissau&lt;/option&gt;
        &lt;option value=&quot;Guyana&quot;&gt;Guyana&lt;/option&gt;
        &lt;option value=&quot;Haiti&quot;&gt;Haiti&lt;/option&gt;
        &lt;option value=&quot;Honduras&quot;&gt;Honduras&lt;/option&gt;
        &lt;option value=&quot;Hungary&quot;&gt;Hungary&lt;/option&gt;
        &lt;option value=&quot;Iceland&quot;&gt;Iceland&lt;/option&gt;
        &lt;option value=&quot;India&quot;&gt;India&lt;/option&gt;
        &lt;option value=&quot;Indonesia&quot;&gt;Indonesia&lt;/option&gt;
        &lt;option value=&quot;Iran&quot;&gt;Iran&lt;/option&gt;
        &lt;option value=&quot;Iraq&quot;&gt;Iraq&lt;/option&gt;
        &lt;option value=&quot;Ireland {Republic}&quot;&gt;Ireland {Republic}&lt;/option&gt;
        &lt;option value=&quot;Israel&quot;&gt;Israel&lt;/option&gt;
        &lt;option value=&quot;Italy&quot;&gt;Italy&lt;/option&gt;
        &lt;option value=&quot;Ivory Coast&quot;&gt;Ivory Coast&lt;/option&gt;
        &lt;option value=&quot;Jamaica&quot;&gt;Jamaica&lt;/option&gt;
        &lt;option value=&quot;Japan&quot;&gt;Japan&lt;/option&gt;
        &lt;option value=&quot;Jordan&quot;&gt;Jordan&lt;/option&gt;
        &lt;option value=&quot;Kazakhstan&quot;&gt;Kazakhstan&lt;/option&gt;
        &lt;option value=&quot;Kenya&quot;&gt;Kenya&lt;/option&gt;
        &lt;option value=&quot;Kiribati&quot;&gt;Kiribati&lt;/option&gt;
        &lt;option value=&quot;Korea North&quot;&gt;Korea North&lt;/option&gt;
        &lt;option value=&quot;Korea South&quot;&gt;Korea South&lt;/option&gt;
        &lt;option value=&quot;Kosovo&quot;&gt;Kosovo&lt;/option&gt;
        &lt;option value=&quot;Kuwait&quot;&gt;Kuwait&lt;/option&gt;
        &lt;option value=&quot;Kyrgyzstan&quot;&gt;Kyrgyzstan&lt;/option&gt;
        &lt;option value=&quot;Laos&quot;&gt;Laos&lt;/option&gt;
        &lt;option value=&quot;Latvia&quot;&gt;Latvia&lt;/option&gt;
        &lt;option value=&quot;Lebanon&quot;&gt;Lebanon&lt;/option&gt;
        &lt;option value=&quot;Lesotho&quot;&gt;Lesotho&lt;/option&gt;
        &lt;option value=&quot;Liberia&quot;&gt;Liberia&lt;/option&gt;
        &lt;option value=&quot;Libya&quot;&gt;Libya&lt;/option&gt;
        &lt;option value=&quot;Liechtenstein&quot;&gt;Liechtenstein&lt;/option&gt;
        &lt;option value=&quot;Lithuania&quot;&gt;Lithuania&lt;/option&gt;
        &lt;option value=&quot;Luxembourg&quot;&gt;Luxembourg&lt;/option&gt;
        &lt;option value=&quot;Macedonia&quot;&gt;Macedonia&lt;/option&gt;
        &lt;option value=&quot;Madagascar&quot;&gt;Madagascar&lt;/option&gt;
        &lt;option value=&quot;Malawi&quot;&gt;Malawi&lt;/option&gt;
        &lt;option value=&quot;Malaysia&quot;&gt;Malaysia&lt;/option&gt;
        &lt;option value=&quot;Maldives&quot;&gt;Maldives&lt;/option&gt;
        &lt;option value=&quot;Mali&quot;&gt;Mali&lt;/option&gt;
        &lt;option value=&quot;Malta&quot;&gt;Malta&lt;/option&gt;
        &lt;option value=&quot;Marshall Islands&quot;&gt;Marshall Islands&lt;/option&gt;
        &lt;option value=&quot;Mauritania&quot;&gt;Mauritania&lt;/option&gt;
        &lt;option value=&quot;Mauritius&quot;&gt;Mauritius&lt;/option&gt;
        &lt;option value=&quot;Mexico&quot;&gt;Mexico&lt;/option&gt;
        &lt;option value=&quot;Micronesia&quot;&gt;Micronesia&lt;/option&gt;
        &lt;option value=&quot;Moldova&quot;&gt;Moldova&lt;/option&gt;
        &lt;option value=&quot;Monaco&quot;&gt;Monaco&lt;/option&gt;
        &lt;option value=&quot;Mongolia&quot;&gt;Mongolia&lt;/option&gt;
        &lt;option value=&quot;Montenegro&quot;&gt;Montenegro&lt;/option&gt;
        &lt;option value=&quot;Morocco&quot;&gt;Morocco&lt;/option&gt;
        &lt;option value=&quot;Mozambique&quot;&gt;Mozambique&lt;/option&gt;
        &lt;option value=&quot;Myanmar, {Burma}&quot;&gt;Myanmar, {Burma}&lt;/option&gt;
        &lt;option value=&quot;Namibia&quot;&gt;Namibia&lt;/option&gt;
        &lt;option value=&quot;Nauru&quot;&gt;Nauru&lt;/option&gt;
        &lt;option value=&quot;Nepal&quot;&gt;Nepal&lt;/option&gt;
        &lt;option value=&quot;Netherlands&quot;&gt;Netherlands&lt;/option&gt;
        &lt;option value=&quot;New Zealand&quot;&gt;New Zealand&lt;/option&gt;
        &lt;option value=&quot;Nicaragua&quot;&gt;Nicaragua&lt;/option&gt;
        &lt;option value=&quot;Niger&quot;&gt;Niger&lt;/option&gt;
        &lt;option value=&quot;Nigeria&quot;&gt;Nigeria&lt;/option&gt;
        &lt;option value=&quot;Norway&quot;&gt;Norway&lt;/option&gt;
        &lt;option value=&quot;Oman&quot;&gt;Oman&lt;/option&gt;
        &lt;option value=&quot;Pakistan&quot;&gt;Pakistan&lt;/option&gt;
        &lt;option value=&quot;Palau&quot;&gt;Palau&lt;/option&gt;
        &lt;option value=&quot;Panama&quot;&gt;Panama&lt;/option&gt;
        &lt;option value=&quot;Papua New Guinea&quot;&gt;Papua New Guinea&lt;/option&gt;
        &lt;option value=&quot;Paraguay&quot;&gt;Paraguay&lt;/option&gt;
        &lt;option value=&quot;Peru&quot;&gt;Peru&lt;/option&gt;
        &lt;option value=&quot;Philippines&quot;&gt;Philippines&lt;/option&gt;
        &lt;option value=&quot;Poland&quot;&gt;Poland&lt;/option&gt;
        &lt;option value=&quot;Portugal&quot;&gt;Portugal&lt;/option&gt;
        &lt;option value=&quot;Qatar&quot;&gt;Qatar&lt;/option&gt;
        &lt;option value=&quot;Romania&quot;&gt;Romania&lt;/option&gt;
        &lt;option value=&quot;Russian Federation&quot;&gt;Russian Federation&lt;/option&gt;
        &lt;option value=&quot;Rwanda&quot;&gt;Rwanda&lt;/option&gt;
        &lt;option value=&quot;St Kitts &amp;amp; Nevis&quot;&gt;St Kitts &amp;amp; Nevis&lt;/option&gt;
        &lt;option value=&quot;St Lucia&quot;&gt;St Lucia&lt;/option&gt;
        &lt;option value=&quot;Saint Vincent &amp;amp; the Grenadines&quot;&gt;Saint Vincent &amp;amp; the Grenadines&lt;/option&gt;
        &lt;option value=&quot;Samoa&quot;&gt;Samoa&lt;/option&gt;
        &lt;option value=&quot;San Marino&quot;&gt;San Marino&lt;/option&gt;
        &lt;option value=&quot;Sao Tome &amp;amp; Principe&quot;&gt;Sao Tome &amp;amp; Principe&lt;/option&gt;
        &lt;option value=&quot;Saudi Arabia&quot;&gt;Saudi Arabia&lt;/option&gt;
        &lt;option value=&quot;Senegal&quot;&gt;Senegal&lt;/option&gt;
        &lt;option value=&quot;Serbia&quot;&gt;Serbia&lt;/option&gt;
        &lt;option value=&quot;Seychelles&quot;&gt;Seychelles&lt;/option&gt;
        &lt;option value=&quot;Sierra Leone&quot;&gt;Sierra Leone&lt;/option&gt;
        &lt;option value=&quot;Singapore&quot;&gt;Singapore&lt;/option&gt;
        &lt;option value=&quot;Slovakia&quot;&gt;Slovakia&lt;/option&gt;
        &lt;option value=&quot;Slovenia&quot;&gt;Slovenia&lt;/option&gt;
        &lt;option value=&quot;Solomon Islands&quot;&gt;Solomon Islands&lt;/option&gt;
        &lt;option value=&quot;Somalia&quot;&gt;Somalia&lt;/option&gt;
        &lt;option value=&quot;South Africa&quot;&gt;South Africa&lt;/option&gt;
        &lt;option value=&quot;South Sudan&quot;&gt;South Sudan&lt;/option&gt;
        &lt;option value=&quot;Spain&quot;&gt;Spain&lt;/option&gt;
        &lt;option value=&quot;Sri Lanka&quot;&gt;Sri Lanka&lt;/option&gt;
        &lt;option value=&quot;Sudan&quot;&gt;Sudan&lt;/option&gt;
        &lt;option value=&quot;Suriname&quot;&gt;Suriname&lt;/option&gt;
        &lt;option value=&quot;Swaziland&quot;&gt;Swaziland&lt;/option&gt;
        &lt;option value=&quot;Sweden&quot;&gt;Sweden&lt;/option&gt;
        &lt;option value=&quot;Switzerland&quot;&gt;Switzerland&lt;/option&gt;
        &lt;option value=&quot;Syria&quot;&gt;Syria&lt;/option&gt;
        &lt;option value=&quot;Taiwan&quot;&gt;Taiwan&lt;/option&gt;
        &lt;option value=&quot;Tajikistan&quot;&gt;Tajikistan&lt;/option&gt;
        &lt;option value=&quot;Tanzania&quot;&gt;Tanzania&lt;/option&gt;
        &lt;option value=&quot;Thailand&quot;&gt;Thailand&lt;/option&gt;
        &lt;option value=&quot;Togo&quot;&gt;Togo&lt;/option&gt;
        &lt;option value=&quot;Tonga&quot;&gt;Tonga&lt;/option&gt;
        &lt;option value=&quot;Trinidad &amp;amp; Tobago&quot;&gt;Trinidad &amp;amp; Tobago&lt;/option&gt;
        &lt;option value=&quot;Tunisia&quot;&gt;Tunisia&lt;/option&gt;
        &lt;option value=&quot;Turkey&quot;&gt;Turkey&lt;/option&gt;
        &lt;option value=&quot;Turkmenistan&quot;&gt;Turkmenistan&lt;/option&gt;
        &lt;option value=&quot;Tuvalu&quot;&gt;Tuvalu&lt;/option&gt;
        &lt;option value=&quot;Uganda&quot;&gt;Uganda&lt;/option&gt;
        &lt;option value=&quot;Ukraine&quot;&gt;Ukraine&lt;/option&gt;
        &lt;option value=&quot;United Arab Emirates&quot;&gt;United Arab Emirates&lt;/option&gt;
        &lt;option value=&quot;Uruguay&quot;&gt;Uruguay&lt;/option&gt;
        &lt;option value=&quot;Uzbekistan&quot;&gt;Uzbekistan&lt;/option&gt;
        &lt;option value=&quot;Vanuatu&quot;&gt;Vanuatu&lt;/option&gt;
        &lt;option value=&quot;Vatican City&quot;&gt;Vatican City&lt;/option&gt;
        &lt;option value=&quot;Venezuela&quot;&gt;Venezuela&lt;/option&gt;
        &lt;option value=&quot;Vietnam&quot;&gt;Vietnam&lt;/option&gt;
        &lt;option value=&quot;Yemen&quot;&gt;Yemen&lt;/option&gt;
        &lt;option value=&quot;Zambia&quot;&gt;Zambia&lt;/option&gt;
        &lt;option value=&quot;Zimbabwe&quot;&gt;Zimbabwe&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- File --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;file_0&quot;&gt;Attach Detailed Itinerary&lt;/label&gt;
    &lt;input type=&quot;file&quot; id=&quot;file_0&quot; name=&quot;file_0&quot; accept=&quot;.txt, .pdf, .doc, .docx&quot;&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'event-planner',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 6,
            'category_id' => 7,
            'name' => 'Expense Tracker',
            'description' => 'With this expense tracker at hand, you can instantly record every one of your expenses and on what you spend. That way, you can generate reports to know where you should start cutting down costs.',
            'builder' => '{"settings":{"name":"Expense Tracker","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Expense Tracker","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"If you\'d like to better manage your expenses fill this form and generate your report.","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"date","title":"date.title","fields":{"id":{"label":"component.id","type":"input","value":"date_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"date","selected":true,"label":"Date"},{"value":"datetime-local","selected":false,"label":"DateTime-Local"},{"value":"time","selected":false,"label":"Time"},{"value":"month","selected":false,"label":"Month"},{"value":"week","selected":false,"label":"Week"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Date","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"min":{"label":"component.minDate","type":"input","value":"","advanced":true,"name":"min"},"max":{"label":"component.maxDate","type":"input","value":"","advanced":true,"name":"max"},"step":{"label":"component.stepNumber","type":"input","value":"","advanced":true,"name":"step"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Category","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Option 1","Option 2","Option 3"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Subcategory","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Option 1","Option 2","Option 3"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Amount","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_2","name":"id"},"label":{"label":"component.label","type":"input","value":"Payment Method","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Cash","Cheque","Credit Card","Debit Card"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Transaction Notes","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Type","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Personal","Bussiness"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":731}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Expense Tracker&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;If you&#039;d like to better manage your expenses fill this form and generate your report.&lt;/p&gt;

&lt;!-- Date --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;date_0&quot;&gt;Date&lt;/label&gt;
    &lt;input type=&quot;date&quot; id=&quot;date_0&quot; name=&quot;date_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Category&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Option 1&quot;&gt;Option 1&lt;/option&gt;
        &lt;option value=&quot;Option 2&quot;&gt;Option 2&lt;/option&gt;
        &lt;option value=&quot;Option 3&quot;&gt;Option 3&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_1&quot;&gt;Subcategory&lt;/label&gt;
    &lt;select id=&quot;selectlist_1&quot; name=&quot;selectlist_1[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Option 1&quot;&gt;Option 1&lt;/option&gt;
        &lt;option value=&quot;Option 2&quot;&gt;Option 2&lt;/option&gt;
        &lt;option value=&quot;Option 3&quot;&gt;Option 3&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Amount&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_2&quot;&gt;Payment Method&lt;/label&gt;
    &lt;select id=&quot;selectlist_2&quot; name=&quot;selectlist_2[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Cash&quot;&gt;Cash&lt;/option&gt;
        &lt;option value=&quot;Cheque&quot;&gt;Cheque&lt;/option&gt;
        &lt;option value=&quot;Credit Card&quot;&gt;Credit Card&lt;/option&gt;
        &lt;option value=&quot;Debit Card&quot;&gt;Debit Card&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Transaction Notes&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;Type&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;Personal&quot;&gt; Personal &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;Bussiness&quot;&gt; Bussiness &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'expense-tracker',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 7,
            'category_id' => 5,
            'name' => 'Online Payment Form',
            'description' => 'An online payment form to sell your products securely on any place you want.',
            'builder' => '{"settings":{"name":"Online Payment Form","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Online Payment Form","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"What would you like to buy?","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"How many widgets would you like to buy?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["1 - $10|1","2 - $19|2","3 - $26|3"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_1","name":"id"},"label":{"label":"component.label","type":"input","value":"What color would you like?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Red","Yellow","Blue"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Email","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Where would you like the widgets shipped?","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"City","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"State","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Country","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["United States","United Kingdom","Australia","Canada","France","----","Afghanistan","Albania","Algeria","Andorra","Angola","Antigua & Deps","Argentina","Armenia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina","Burundi","Cambodia","Cameroon","Cape Verde","Central African Rep","Chad","Chile","China","Colombia","Comoros","Congo","Congo {Democratic Rep}","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland {Republic}","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea North","Korea South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar, {Burma}","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russian Federation","Rwanda","St Kitts & Nevis","St Lucia","Saint Vincent & the Grenadines","Samoa","San Marino","Sao Tome & Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-right","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Would you like to pay by check or credit card?","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Check","Credit Card"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_1","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Cstrong\u003EPlease Read\u003C\/strong\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_2","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Csmall\u003EYou will be taken to a secure payment page after submitting this form. Please enter your credit card information on that page to complete your purchase.\u003C\/small\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_3","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Csmall\u003EThank you!\u003C\/small\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":891}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Online Payment Form&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;What would you like to buy?&lt;/p&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;How many widgets would you like to buy?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;1&quot;&gt; 1 - $10 &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;2&quot;&gt; 2 - $19 &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_2&quot; value=&quot;3&quot;&gt; 3 - $26 &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_1&quot;&gt;What color would you like?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_0&quot; value=&quot;Red&quot;&gt; Red &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_1&quot; value=&quot;Yellow&quot;&gt; Yellow &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_2&quot; value=&quot;Blue&quot;&gt; Blue &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_1&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Email&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Where would you like the widgets shipped?&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;City&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;State&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-right&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Country&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
        &lt;option value=&quot;United Kingdom&quot;&gt;United Kingdom&lt;/option&gt;
        &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
        &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
        &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
        &lt;option value=&quot;----&quot;&gt;----&lt;/option&gt;
        &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
        &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
        &lt;option value=&quot;Algeria&quot;&gt;Algeria&lt;/option&gt;
        &lt;option value=&quot;Andorra&quot;&gt;Andorra&lt;/option&gt;
        &lt;option value=&quot;Angola&quot;&gt;Angola&lt;/option&gt;
        &lt;option value=&quot;Antigua &amp;amp; Deps&quot;&gt;Antigua &amp;amp; Deps&lt;/option&gt;
        &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
        &lt;option value=&quot;Armenia&quot;&gt;Armenia&lt;/option&gt;
        &lt;option value=&quot;Austria&quot;&gt;Austria&lt;/option&gt;
        &lt;option value=&quot;Azerbaijan&quot;&gt;Azerbaijan&lt;/option&gt;
        &lt;option value=&quot;Bahamas&quot;&gt;Bahamas&lt;/option&gt;
        &lt;option value=&quot;Bahrain&quot;&gt;Bahrain&lt;/option&gt;
        &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
        &lt;option value=&quot;Barbados&quot;&gt;Barbados&lt;/option&gt;
        &lt;option value=&quot;Belarus&quot;&gt;Belarus&lt;/option&gt;
        &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
        &lt;option value=&quot;Belize&quot;&gt;Belize&lt;/option&gt;
        &lt;option value=&quot;Benin&quot;&gt;Benin&lt;/option&gt;
        &lt;option value=&quot;Bhutan&quot;&gt;Bhutan&lt;/option&gt;
        &lt;option value=&quot;Bolivia&quot;&gt;Bolivia&lt;/option&gt;
        &lt;option value=&quot;Bosnia Herzegovina&quot;&gt;Bosnia Herzegovina&lt;/option&gt;
        &lt;option value=&quot;Botswana&quot;&gt;Botswana&lt;/option&gt;
        &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
        &lt;option value=&quot;Brunei&quot;&gt;Brunei&lt;/option&gt;
        &lt;option value=&quot;Bulgaria&quot;&gt;Bulgaria&lt;/option&gt;
        &lt;option value=&quot;Burkina&quot;&gt;Burkina&lt;/option&gt;
        &lt;option value=&quot;Burundi&quot;&gt;Burundi&lt;/option&gt;
        &lt;option value=&quot;Cambodia&quot;&gt;Cambodia&lt;/option&gt;
        &lt;option value=&quot;Cameroon&quot;&gt;Cameroon&lt;/option&gt;
        &lt;option value=&quot;Cape Verde&quot;&gt;Cape Verde&lt;/option&gt;
        &lt;option value=&quot;Central African Rep&quot;&gt;Central African Rep&lt;/option&gt;
        &lt;option value=&quot;Chad&quot;&gt;Chad&lt;/option&gt;
        &lt;option value=&quot;Chile&quot;&gt;Chile&lt;/option&gt;
        &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
        &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
        &lt;option value=&quot;Comoros&quot;&gt;Comoros&lt;/option&gt;
        &lt;option value=&quot;Congo&quot;&gt;Congo&lt;/option&gt;
        &lt;option value=&quot;Congo {Democratic Rep}&quot;&gt;Congo {Democratic Rep}&lt;/option&gt;
        &lt;option value=&quot;Costa Rica&quot;&gt;Costa Rica&lt;/option&gt;
        &lt;option value=&quot;Croatia&quot;&gt;Croatia&lt;/option&gt;
        &lt;option value=&quot;Cuba&quot;&gt;Cuba&lt;/option&gt;
        &lt;option value=&quot;Cyprus&quot;&gt;Cyprus&lt;/option&gt;
        &lt;option value=&quot;Czech Republic&quot;&gt;Czech Republic&lt;/option&gt;
        &lt;option value=&quot;Denmark&quot;&gt;Denmark&lt;/option&gt;
        &lt;option value=&quot;Djibouti&quot;&gt;Djibouti&lt;/option&gt;
        &lt;option value=&quot;Dominica&quot;&gt;Dominica&lt;/option&gt;
        &lt;option value=&quot;Dominican Republic&quot;&gt;Dominican Republic&lt;/option&gt;
        &lt;option value=&quot;East Timor&quot;&gt;East Timor&lt;/option&gt;
        &lt;option value=&quot;Ecuador&quot;&gt;Ecuador&lt;/option&gt;
        &lt;option value=&quot;Egypt&quot;&gt;Egypt&lt;/option&gt;
        &lt;option value=&quot;El Salvador&quot;&gt;El Salvador&lt;/option&gt;
        &lt;option value=&quot;Equatorial Guinea&quot;&gt;Equatorial Guinea&lt;/option&gt;
        &lt;option value=&quot;Eritrea&quot;&gt;Eritrea&lt;/option&gt;
        &lt;option value=&quot;Estonia&quot;&gt;Estonia&lt;/option&gt;
        &lt;option value=&quot;Ethiopia&quot;&gt;Ethiopia&lt;/option&gt;
        &lt;option value=&quot;Fiji&quot;&gt;Fiji&lt;/option&gt;
        &lt;option value=&quot;Finland&quot;&gt;Finland&lt;/option&gt;
        &lt;option value=&quot;Gabon&quot;&gt;Gabon&lt;/option&gt;
        &lt;option value=&quot;Gambia&quot;&gt;Gambia&lt;/option&gt;
        &lt;option value=&quot;Georgia&quot;&gt;Georgia&lt;/option&gt;
        &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
        &lt;option value=&quot;Ghana&quot;&gt;Ghana&lt;/option&gt;
        &lt;option value=&quot;Greece&quot;&gt;Greece&lt;/option&gt;
        &lt;option value=&quot;Grenada&quot;&gt;Grenada&lt;/option&gt;
        &lt;option value=&quot;Guatemala&quot;&gt;Guatemala&lt;/option&gt;
        &lt;option value=&quot;Guinea&quot;&gt;Guinea&lt;/option&gt;
        &lt;option value=&quot;Guinea-Bissau&quot;&gt;Guinea-Bissau&lt;/option&gt;
        &lt;option value=&quot;Guyana&quot;&gt;Guyana&lt;/option&gt;
        &lt;option value=&quot;Haiti&quot;&gt;Haiti&lt;/option&gt;
        &lt;option value=&quot;Honduras&quot;&gt;Honduras&lt;/option&gt;
        &lt;option value=&quot;Hungary&quot;&gt;Hungary&lt;/option&gt;
        &lt;option value=&quot;Iceland&quot;&gt;Iceland&lt;/option&gt;
        &lt;option value=&quot;India&quot;&gt;India&lt;/option&gt;
        &lt;option value=&quot;Indonesia&quot;&gt;Indonesia&lt;/option&gt;
        &lt;option value=&quot;Iran&quot;&gt;Iran&lt;/option&gt;
        &lt;option value=&quot;Iraq&quot;&gt;Iraq&lt;/option&gt;
        &lt;option value=&quot;Ireland {Republic}&quot;&gt;Ireland {Republic}&lt;/option&gt;
        &lt;option value=&quot;Israel&quot;&gt;Israel&lt;/option&gt;
        &lt;option value=&quot;Italy&quot;&gt;Italy&lt;/option&gt;
        &lt;option value=&quot;Ivory Coast&quot;&gt;Ivory Coast&lt;/option&gt;
        &lt;option value=&quot;Jamaica&quot;&gt;Jamaica&lt;/option&gt;
        &lt;option value=&quot;Japan&quot;&gt;Japan&lt;/option&gt;
        &lt;option value=&quot;Jordan&quot;&gt;Jordan&lt;/option&gt;
        &lt;option value=&quot;Kazakhstan&quot;&gt;Kazakhstan&lt;/option&gt;
        &lt;option value=&quot;Kenya&quot;&gt;Kenya&lt;/option&gt;
        &lt;option value=&quot;Kiribati&quot;&gt;Kiribati&lt;/option&gt;
        &lt;option value=&quot;Korea North&quot;&gt;Korea North&lt;/option&gt;
        &lt;option value=&quot;Korea South&quot;&gt;Korea South&lt;/option&gt;
        &lt;option value=&quot;Kosovo&quot;&gt;Kosovo&lt;/option&gt;
        &lt;option value=&quot;Kuwait&quot;&gt;Kuwait&lt;/option&gt;
        &lt;option value=&quot;Kyrgyzstan&quot;&gt;Kyrgyzstan&lt;/option&gt;
        &lt;option value=&quot;Laos&quot;&gt;Laos&lt;/option&gt;
        &lt;option value=&quot;Latvia&quot;&gt;Latvia&lt;/option&gt;
        &lt;option value=&quot;Lebanon&quot;&gt;Lebanon&lt;/option&gt;
        &lt;option value=&quot;Lesotho&quot;&gt;Lesotho&lt;/option&gt;
        &lt;option value=&quot;Liberia&quot;&gt;Liberia&lt;/option&gt;
        &lt;option value=&quot;Libya&quot;&gt;Libya&lt;/option&gt;
        &lt;option value=&quot;Liechtenstein&quot;&gt;Liechtenstein&lt;/option&gt;
        &lt;option value=&quot;Lithuania&quot;&gt;Lithuania&lt;/option&gt;
        &lt;option value=&quot;Luxembourg&quot;&gt;Luxembourg&lt;/option&gt;
        &lt;option value=&quot;Macedonia&quot;&gt;Macedonia&lt;/option&gt;
        &lt;option value=&quot;Madagascar&quot;&gt;Madagascar&lt;/option&gt;
        &lt;option value=&quot;Malawi&quot;&gt;Malawi&lt;/option&gt;
        &lt;option value=&quot;Malaysia&quot;&gt;Malaysia&lt;/option&gt;
        &lt;option value=&quot;Maldives&quot;&gt;Maldives&lt;/option&gt;
        &lt;option value=&quot;Mali&quot;&gt;Mali&lt;/option&gt;
        &lt;option value=&quot;Malta&quot;&gt;Malta&lt;/option&gt;
        &lt;option value=&quot;Marshall Islands&quot;&gt;Marshall Islands&lt;/option&gt;
        &lt;option value=&quot;Mauritania&quot;&gt;Mauritania&lt;/option&gt;
        &lt;option value=&quot;Mauritius&quot;&gt;Mauritius&lt;/option&gt;
        &lt;option value=&quot;Mexico&quot;&gt;Mexico&lt;/option&gt;
        &lt;option value=&quot;Micronesia&quot;&gt;Micronesia&lt;/option&gt;
        &lt;option value=&quot;Moldova&quot;&gt;Moldova&lt;/option&gt;
        &lt;option value=&quot;Monaco&quot;&gt;Monaco&lt;/option&gt;
        &lt;option value=&quot;Mongolia&quot;&gt;Mongolia&lt;/option&gt;
        &lt;option value=&quot;Montenegro&quot;&gt;Montenegro&lt;/option&gt;
        &lt;option value=&quot;Morocco&quot;&gt;Morocco&lt;/option&gt;
        &lt;option value=&quot;Mozambique&quot;&gt;Mozambique&lt;/option&gt;
        &lt;option value=&quot;Myanmar, {Burma}&quot;&gt;Myanmar, {Burma}&lt;/option&gt;
        &lt;option value=&quot;Namibia&quot;&gt;Namibia&lt;/option&gt;
        &lt;option value=&quot;Nauru&quot;&gt;Nauru&lt;/option&gt;
        &lt;option value=&quot;Nepal&quot;&gt;Nepal&lt;/option&gt;
        &lt;option value=&quot;Netherlands&quot;&gt;Netherlands&lt;/option&gt;
        &lt;option value=&quot;New Zealand&quot;&gt;New Zealand&lt;/option&gt;
        &lt;option value=&quot;Nicaragua&quot;&gt;Nicaragua&lt;/option&gt;
        &lt;option value=&quot;Niger&quot;&gt;Niger&lt;/option&gt;
        &lt;option value=&quot;Nigeria&quot;&gt;Nigeria&lt;/option&gt;
        &lt;option value=&quot;Norway&quot;&gt;Norway&lt;/option&gt;
        &lt;option value=&quot;Oman&quot;&gt;Oman&lt;/option&gt;
        &lt;option value=&quot;Pakistan&quot;&gt;Pakistan&lt;/option&gt;
        &lt;option value=&quot;Palau&quot;&gt;Palau&lt;/option&gt;
        &lt;option value=&quot;Panama&quot;&gt;Panama&lt;/option&gt;
        &lt;option value=&quot;Papua New Guinea&quot;&gt;Papua New Guinea&lt;/option&gt;
        &lt;option value=&quot;Paraguay&quot;&gt;Paraguay&lt;/option&gt;
        &lt;option value=&quot;Peru&quot;&gt;Peru&lt;/option&gt;
        &lt;option value=&quot;Philippines&quot;&gt;Philippines&lt;/option&gt;
        &lt;option value=&quot;Poland&quot;&gt;Poland&lt;/option&gt;
        &lt;option value=&quot;Portugal&quot;&gt;Portugal&lt;/option&gt;
        &lt;option value=&quot;Qatar&quot;&gt;Qatar&lt;/option&gt;
        &lt;option value=&quot;Romania&quot;&gt;Romania&lt;/option&gt;
        &lt;option value=&quot;Russian Federation&quot;&gt;Russian Federation&lt;/option&gt;
        &lt;option value=&quot;Rwanda&quot;&gt;Rwanda&lt;/option&gt;
        &lt;option value=&quot;St Kitts &amp;amp; Nevis&quot;&gt;St Kitts &amp;amp; Nevis&lt;/option&gt;
        &lt;option value=&quot;St Lucia&quot;&gt;St Lucia&lt;/option&gt;
        &lt;option value=&quot;Saint Vincent &amp;amp; the Grenadines&quot;&gt;Saint Vincent &amp;amp; the Grenadines&lt;/option&gt;
        &lt;option value=&quot;Samoa&quot;&gt;Samoa&lt;/option&gt;
        &lt;option value=&quot;San Marino&quot;&gt;San Marino&lt;/option&gt;
        &lt;option value=&quot;Sao Tome &amp;amp; Principe&quot;&gt;Sao Tome &amp;amp; Principe&lt;/option&gt;
        &lt;option value=&quot;Saudi Arabia&quot;&gt;Saudi Arabia&lt;/option&gt;
        &lt;option value=&quot;Senegal&quot;&gt;Senegal&lt;/option&gt;
        &lt;option value=&quot;Serbia&quot;&gt;Serbia&lt;/option&gt;
        &lt;option value=&quot;Seychelles&quot;&gt;Seychelles&lt;/option&gt;
        &lt;option value=&quot;Sierra Leone&quot;&gt;Sierra Leone&lt;/option&gt;
        &lt;option value=&quot;Singapore&quot;&gt;Singapore&lt;/option&gt;
        &lt;option value=&quot;Slovakia&quot;&gt;Slovakia&lt;/option&gt;
        &lt;option value=&quot;Slovenia&quot;&gt;Slovenia&lt;/option&gt;
        &lt;option value=&quot;Solomon Islands&quot;&gt;Solomon Islands&lt;/option&gt;
        &lt;option value=&quot;Somalia&quot;&gt;Somalia&lt;/option&gt;
        &lt;option value=&quot;South Africa&quot;&gt;South Africa&lt;/option&gt;
        &lt;option value=&quot;South Sudan&quot;&gt;South Sudan&lt;/option&gt;
        &lt;option value=&quot;Spain&quot;&gt;Spain&lt;/option&gt;
        &lt;option value=&quot;Sri Lanka&quot;&gt;Sri Lanka&lt;/option&gt;
        &lt;option value=&quot;Sudan&quot;&gt;Sudan&lt;/option&gt;
        &lt;option value=&quot;Suriname&quot;&gt;Suriname&lt;/option&gt;
        &lt;option value=&quot;Swaziland&quot;&gt;Swaziland&lt;/option&gt;
        &lt;option value=&quot;Sweden&quot;&gt;Sweden&lt;/option&gt;
        &lt;option value=&quot;Switzerland&quot;&gt;Switzerland&lt;/option&gt;
        &lt;option value=&quot;Syria&quot;&gt;Syria&lt;/option&gt;
        &lt;option value=&quot;Taiwan&quot;&gt;Taiwan&lt;/option&gt;
        &lt;option value=&quot;Tajikistan&quot;&gt;Tajikistan&lt;/option&gt;
        &lt;option value=&quot;Tanzania&quot;&gt;Tanzania&lt;/option&gt;
        &lt;option value=&quot;Thailand&quot;&gt;Thailand&lt;/option&gt;
        &lt;option value=&quot;Togo&quot;&gt;Togo&lt;/option&gt;
        &lt;option value=&quot;Tonga&quot;&gt;Tonga&lt;/option&gt;
        &lt;option value=&quot;Trinidad &amp;amp; Tobago&quot;&gt;Trinidad &amp;amp; Tobago&lt;/option&gt;
        &lt;option value=&quot;Tunisia&quot;&gt;Tunisia&lt;/option&gt;
        &lt;option value=&quot;Turkey&quot;&gt;Turkey&lt;/option&gt;
        &lt;option value=&quot;Turkmenistan&quot;&gt;Turkmenistan&lt;/option&gt;
        &lt;option value=&quot;Tuvalu&quot;&gt;Tuvalu&lt;/option&gt;
        &lt;option value=&quot;Uganda&quot;&gt;Uganda&lt;/option&gt;
        &lt;option value=&quot;Ukraine&quot;&gt;Ukraine&lt;/option&gt;
        &lt;option value=&quot;United Arab Emirates&quot;&gt;United Arab Emirates&lt;/option&gt;
        &lt;option value=&quot;Uruguay&quot;&gt;Uruguay&lt;/option&gt;
        &lt;option value=&quot;Uzbekistan&quot;&gt;Uzbekistan&lt;/option&gt;
        &lt;option value=&quot;Vanuatu&quot;&gt;Vanuatu&lt;/option&gt;
        &lt;option value=&quot;Vatican City&quot;&gt;Vatican City&lt;/option&gt;
        &lt;option value=&quot;Venezuela&quot;&gt;Venezuela&lt;/option&gt;
        &lt;option value=&quot;Vietnam&quot;&gt;Vietnam&lt;/option&gt;
        &lt;option value=&quot;Yemen&quot;&gt;Yemen&lt;/option&gt;
        &lt;option value=&quot;Zambia&quot;&gt;Zambia&lt;/option&gt;
        &lt;option value=&quot;Zimbabwe&quot;&gt;Zimbabwe&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_1&quot;&gt;Would you like to pay by check or credit card?&lt;/label&gt;
    &lt;select id=&quot;selectlist_1&quot; name=&quot;selectlist_1[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Check&quot;&gt;Check&lt;/option&gt;
        &lt;option value=&quot;Credit Card&quot;&gt;Credit Card&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;strong&gt;Please Read&lt;/strong&gt;&lt;/p&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;small&gt;You will be taken to a secure payment page after submitting this form. Please enter your credit card information on that page to complete your purchase.&lt;/small&gt;&lt;/p&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;small&gt;Thank you!&lt;/small&gt;&lt;/p&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'online-payment-form',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 8,
            'category_id' => 2,
            'name' => 'Trivia Quiz',
            'description' => 'Create trivia quizes and receive online responses to the quiz.',
            'builder' => '{"settings":{"name":"Trivia Quiz","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":["Untitled Step","Untitled Step","Untitled Step","Untitled Step"],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":true,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Trivia Quiz","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Cstrong\u003EFill out this trivia quiz for fun!\u003C\/strong\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_1","name":"id"},"text":{"label":"component.text","type":"textarea","value":"\u003Csmall\u003EYou will receive 5 points for every correct answer.\u003C\/small\u003E","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Which country is also known as the land of the rising sun?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Japan|5","China|0","Australia|0"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"pagebreak","title":"pagebreak.title","fields":{"id":{"label":"component.id","type":"input","value":"pagebreak_0","name":"id"},"prev":{"label":"component.prev","type":"input","value":"","name":"prev"},"next":{"label":"component.next","type":"input","value":"","name":"next"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Which is the smallest country in the world?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Liechtenstein|0","Vatican City|5","Monaco|0"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"pagebreak","title":"pagebreak.title","fields":{"id":{"label":"component.id","type":"input","value":"pagebreak_1","name":"id"},"prev":{"label":"component.prev","type":"input","value":"","name":"prev"},"next":{"label":"component.next","type":"input","value":"","name":"next"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_2","name":"id"},"label":{"label":"component.label","type":"input","value":"What food group has the highest level of protein?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["Bread|0","Vegetables|0","Meat|5"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"pagebreak","title":"pagebreak.title","fields":{"id":{"label":"component.id","type":"input","value":"pagebreak_2","name":"id"},"prev":{"label":"component.prev","type":"input","value":"","name":"prev"},"next":{"label":"component.next","type":"input","value":"","name":"next"}},"fresh":false},{"name":"radio","title":"radio.title","fields":{"id":{"label":"component.groupName","type":"input","value":"radio_3","name":"id"},"label":{"label":"component.label","type":"input","value":"How many words are there in the English language?","name":"label"},"radios":{"label":"component.radios","type":"textarea-split","value":["2 Million+|0","170,000|5","70,000|0","500,000|0"],"name":"radios"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"radio-inline","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"inline":{"label":"component.inline","type":"checkbox","value":false,"advanced":true,"name":"inline"}},"fresh":false},{"name":"snippet","title":"snippet.title","fields":{"id":{"label":"component.id","type":"input","value":"snippet_0","name":"id"},"snippet":{"label":"component.htmlCode","type":"textarea","value":"\u003Cdiv id=\u0022result\u0022 class=\u0022well\u0022\u003EYour score is \u003Cspan id=\u0022score\u0022 class=\u0022label label-default\u0022\u003E0\u003C\/span\u003E. Thanks for your time!\u003C\/div\u003E","name":"snippet"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":883}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;!-- Steps --&gt;
&lt;div class=&quot;steps&quot;&gt;
    &lt;div class=&quot;step no-title current&quot;&gt;
        &lt;div class=&quot;stage&quot;&gt;1&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class=&quot;step no-title&quot;&gt;
        &lt;div class=&quot;stage&quot;&gt;2&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class=&quot;step no-title&quot;&gt;
        &lt;div class=&quot;stage&quot;&gt;3&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class=&quot;step no-title&quot;&gt;
        &lt;div class=&quot;stage&quot;&gt;4&lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Trivia Quiz&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;strong&gt;Fill out this trivia quiz for fun!&lt;/strong&gt;&lt;/p&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;&lt;small&gt;You will receive 5 points for every correct answer.&lt;/small&gt;&lt;/p&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_0&quot;&gt;Which country is also known as the land of the rising sun?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_0&quot; value=&quot;5&quot;&gt; Japan &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_1&quot; value=&quot;0&quot;&gt; China &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_0_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_0&quot; id=&quot;radio_0_2&quot; value=&quot;0&quot;&gt; Australia &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_0&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Page Break --&gt;
&lt;div class=&quot;page-break&quot; data-button-previous=&quot;&quot; data-button-next=&quot;&quot;&gt;&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_1&quot;&gt;Which is the smallest country in the world?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_0&quot; value=&quot;0&quot;&gt; Liechtenstein &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_1&quot; value=&quot;5&quot;&gt; Vatican City &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_1_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_1&quot; id=&quot;radio_1_2&quot; value=&quot;0&quot;&gt; Monaco &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_1&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Page Break --&gt;
&lt;div class=&quot;page-break&quot; data-button-previous=&quot;&quot; data-button-next=&quot;&quot;&gt;&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_2&quot;&gt;What food group has the highest level of protein?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_0&quot; value=&quot;0&quot;&gt; Bread &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_1&quot; value=&quot;0&quot;&gt; Vegetables &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_2_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_2&quot; id=&quot;radio_2_2&quot; value=&quot;5&quot;&gt; Meat &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_2&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Page Break --&gt;
&lt;div class=&quot;page-break&quot; data-button-previous=&quot;&quot; data-button-next=&quot;&quot;&gt;&lt;/div&gt;

&lt;!-- Radio --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;radio_3&quot;&gt;How many words are there in the English language?&lt;/label&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_0&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_0&quot; value=&quot;0&quot;&gt; 2 Million+ &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_1&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_1&quot; value=&quot;5&quot;&gt; 170,000 &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_2&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_2&quot; value=&quot;0&quot;&gt; 70,000 &lt;/label&gt;
    &lt;/div&gt;
    &lt;div class=&quot;radio&quot;&gt;
        &lt;label for=&quot;radio_3_3&quot; class=&quot;radio-inline&quot;&gt;
        &lt;input type=&quot;radio&quot; name=&quot;radio_3&quot; id=&quot;radio_3_3&quot; value=&quot;0&quot;&gt; 500,000 &lt;/label&gt;
    &lt;/div&gt;
    &lt;span id=&quot;radio_3&quot;&gt;&lt;/span&gt;
&lt;/div&gt;

&lt;!-- Snippet --&gt;
&lt;div class=&quot;snippet&quot;&gt;&lt;div id=&quot;result&quot; class=&quot;well&quot;&gt;Your score is &lt;span id=&quot;score&quot; class=&quot;label label-default&quot;&gt;0&lt;/span&gt;. Thanks for your time!&lt;/div&gt;&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 1,
            'slug' => 'trivia-quiz',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 9,
            'category_id' => 7,
            'name' => 'Address Book',
            'description' => 'Use our online address book to easily input, organize, and store your personal contacts.',
            'builder' => '{"settings":{"name":"Address Book","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Address Book","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"type"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"file","title":"file.title","fields":{"id":{"label":"component.id","type":"input","value":"file_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Photo","name":"label"},"accept":{"label":"component.accept","type":"input","value":".gif, .jpg, .png","name":"accept"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"minSize":{"label":"component.minSize","type":"input","value":"","advanced":true,"name":"minSize"},"maxSize":{"label":"component.maxSize","type":"input","value":"","advanced":true,"name":"maxSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Address","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"City","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_3","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"State","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Country","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["United States","United Kingdom","Australia","Canada","France","----","Afghanistan","Albania","Algeria","Andorra","Angola","Antigua \u0026 Deps","Argentina","Armenia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina","Burundi","Cambodia","Cameroon","Cape Verde","Central African Rep","Chad","Chile","China","Colombia","Comoros","Congo","Congo {Democratic Rep}","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland {Republic}","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea North","Korea South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar, {Burma}","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russian Federation","Rwanda","St Kitts \u0026 Nevis","St Lucia","Saint Vincent \u0026 the Grenadines","Samoa","San Marino","Sao Tome \u0026 Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad \u0026 Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-right","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_4","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":false,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":true,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Web Site","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Email Address","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_5","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":false,"label":"Text"},{"value":"tel","selected":true,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Home Phone","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_6","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":false,"label":"Text"},{"value":"tel","selected":true,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Cell Phone","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"date","title":"date.title","fields":{"id":{"label":"component.id","type":"input","value":"date_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"date","selected":true,"label":"Date"},{"value":"datetime-local","selected":false,"label":"DateTime-Local"},{"value":"time","selected":false,"label":"Time"},{"value":"month","selected":false,"label":"Month"},{"value":"week","selected":false,"label":"Week"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Birthday","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"min":{"label":"component.minDate","type":"input","value":"","advanced":true,"name":"min"},"max":{"label":"component.maxDate","type":"input","value":"","advanced":true,"name":"max"},"step":{"label":"component.stepNumber","type":"input","value":"","advanced":true,"name":"step"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Notes","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":792}',
            'html' => '&lt;form id=&quot;form-app&quot; enctype=&quot;multipart/form-data&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Address Book&lt;/h3&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- File --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;file_0&quot;&gt;Photo&lt;/label&gt;
    &lt;input type=&quot;file&quot; id=&quot;file_0&quot; name=&quot;file_0&quot; accept=&quot;.gif, .jpg, .png&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;Address&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;City&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_3&quot;&gt;State&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_3&quot; name=&quot;text_3&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-right&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Country&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
        &lt;option value=&quot;United Kingdom&quot;&gt;United Kingdom&lt;/option&gt;
        &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
        &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
        &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
        &lt;option value=&quot;----&quot;&gt;----&lt;/option&gt;
        &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
        &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
        &lt;option value=&quot;Algeria&quot;&gt;Algeria&lt;/option&gt;
        &lt;option value=&quot;Andorra&quot;&gt;Andorra&lt;/option&gt;
        &lt;option value=&quot;Angola&quot;&gt;Angola&lt;/option&gt;
        &lt;option value=&quot;Antigua &amp;amp; Deps&quot;&gt;Antigua &amp;amp; Deps&lt;/option&gt;
        &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
        &lt;option value=&quot;Armenia&quot;&gt;Armenia&lt;/option&gt;
        &lt;option value=&quot;Austria&quot;&gt;Austria&lt;/option&gt;
        &lt;option value=&quot;Azerbaijan&quot;&gt;Azerbaijan&lt;/option&gt;
        &lt;option value=&quot;Bahamas&quot;&gt;Bahamas&lt;/option&gt;
        &lt;option value=&quot;Bahrain&quot;&gt;Bahrain&lt;/option&gt;
        &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
        &lt;option value=&quot;Barbados&quot;&gt;Barbados&lt;/option&gt;
        &lt;option value=&quot;Belarus&quot;&gt;Belarus&lt;/option&gt;
        &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
        &lt;option value=&quot;Belize&quot;&gt;Belize&lt;/option&gt;
        &lt;option value=&quot;Benin&quot;&gt;Benin&lt;/option&gt;
        &lt;option value=&quot;Bhutan&quot;&gt;Bhutan&lt;/option&gt;
        &lt;option value=&quot;Bolivia&quot;&gt;Bolivia&lt;/option&gt;
        &lt;option value=&quot;Bosnia Herzegovina&quot;&gt;Bosnia Herzegovina&lt;/option&gt;
        &lt;option value=&quot;Botswana&quot;&gt;Botswana&lt;/option&gt;
        &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
        &lt;option value=&quot;Brunei&quot;&gt;Brunei&lt;/option&gt;
        &lt;option value=&quot;Bulgaria&quot;&gt;Bulgaria&lt;/option&gt;
        &lt;option value=&quot;Burkina&quot;&gt;Burkina&lt;/option&gt;
        &lt;option value=&quot;Burundi&quot;&gt;Burundi&lt;/option&gt;
        &lt;option value=&quot;Cambodia&quot;&gt;Cambodia&lt;/option&gt;
        &lt;option value=&quot;Cameroon&quot;&gt;Cameroon&lt;/option&gt;
        &lt;option value=&quot;Cape Verde&quot;&gt;Cape Verde&lt;/option&gt;
        &lt;option value=&quot;Central African Rep&quot;&gt;Central African Rep&lt;/option&gt;
        &lt;option value=&quot;Chad&quot;&gt;Chad&lt;/option&gt;
        &lt;option value=&quot;Chile&quot;&gt;Chile&lt;/option&gt;
        &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
        &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
        &lt;option value=&quot;Comoros&quot;&gt;Comoros&lt;/option&gt;
        &lt;option value=&quot;Congo&quot;&gt;Congo&lt;/option&gt;
        &lt;option value=&quot;Congo {Democratic Rep}&quot;&gt;Congo {Democratic Rep}&lt;/option&gt;
        &lt;option value=&quot;Costa Rica&quot;&gt;Costa Rica&lt;/option&gt;
        &lt;option value=&quot;Croatia&quot;&gt;Croatia&lt;/option&gt;
        &lt;option value=&quot;Cuba&quot;&gt;Cuba&lt;/option&gt;
        &lt;option value=&quot;Cyprus&quot;&gt;Cyprus&lt;/option&gt;
        &lt;option value=&quot;Czech Republic&quot;&gt;Czech Republic&lt;/option&gt;
        &lt;option value=&quot;Denmark&quot;&gt;Denmark&lt;/option&gt;
        &lt;option value=&quot;Djibouti&quot;&gt;Djibouti&lt;/option&gt;
        &lt;option value=&quot;Dominica&quot;&gt;Dominica&lt;/option&gt;
        &lt;option value=&quot;Dominican Republic&quot;&gt;Dominican Republic&lt;/option&gt;
        &lt;option value=&quot;East Timor&quot;&gt;East Timor&lt;/option&gt;
        &lt;option value=&quot;Ecuador&quot;&gt;Ecuador&lt;/option&gt;
        &lt;option value=&quot;Egypt&quot;&gt;Egypt&lt;/option&gt;
        &lt;option value=&quot;El Salvador&quot;&gt;El Salvador&lt;/option&gt;
        &lt;option value=&quot;Equatorial Guinea&quot;&gt;Equatorial Guinea&lt;/option&gt;
        &lt;option value=&quot;Eritrea&quot;&gt;Eritrea&lt;/option&gt;
        &lt;option value=&quot;Estonia&quot;&gt;Estonia&lt;/option&gt;
        &lt;option value=&quot;Ethiopia&quot;&gt;Ethiopia&lt;/option&gt;
        &lt;option value=&quot;Fiji&quot;&gt;Fiji&lt;/option&gt;
        &lt;option value=&quot;Finland&quot;&gt;Finland&lt;/option&gt;
        &lt;option value=&quot;Gabon&quot;&gt;Gabon&lt;/option&gt;
        &lt;option value=&quot;Gambia&quot;&gt;Gambia&lt;/option&gt;
        &lt;option value=&quot;Georgia&quot;&gt;Georgia&lt;/option&gt;
        &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
        &lt;option value=&quot;Ghana&quot;&gt;Ghana&lt;/option&gt;
        &lt;option value=&quot;Greece&quot;&gt;Greece&lt;/option&gt;
        &lt;option value=&quot;Grenada&quot;&gt;Grenada&lt;/option&gt;
        &lt;option value=&quot;Guatemala&quot;&gt;Guatemala&lt;/option&gt;
        &lt;option value=&quot;Guinea&quot;&gt;Guinea&lt;/option&gt;
        &lt;option value=&quot;Guinea-Bissau&quot;&gt;Guinea-Bissau&lt;/option&gt;
        &lt;option value=&quot;Guyana&quot;&gt;Guyana&lt;/option&gt;
        &lt;option value=&quot;Haiti&quot;&gt;Haiti&lt;/option&gt;
        &lt;option value=&quot;Honduras&quot;&gt;Honduras&lt;/option&gt;
        &lt;option value=&quot;Hungary&quot;&gt;Hungary&lt;/option&gt;
        &lt;option value=&quot;Iceland&quot;&gt;Iceland&lt;/option&gt;
        &lt;option value=&quot;India&quot;&gt;India&lt;/option&gt;
        &lt;option value=&quot;Indonesia&quot;&gt;Indonesia&lt;/option&gt;
        &lt;option value=&quot;Iran&quot;&gt;Iran&lt;/option&gt;
        &lt;option value=&quot;Iraq&quot;&gt;Iraq&lt;/option&gt;
        &lt;option value=&quot;Ireland {Republic}&quot;&gt;Ireland {Republic}&lt;/option&gt;
        &lt;option value=&quot;Israel&quot;&gt;Israel&lt;/option&gt;
        &lt;option value=&quot;Italy&quot;&gt;Italy&lt;/option&gt;
        &lt;option value=&quot;Ivory Coast&quot;&gt;Ivory Coast&lt;/option&gt;
        &lt;option value=&quot;Jamaica&quot;&gt;Jamaica&lt;/option&gt;
        &lt;option value=&quot;Japan&quot;&gt;Japan&lt;/option&gt;
        &lt;option value=&quot;Jordan&quot;&gt;Jordan&lt;/option&gt;
        &lt;option value=&quot;Kazakhstan&quot;&gt;Kazakhstan&lt;/option&gt;
        &lt;option value=&quot;Kenya&quot;&gt;Kenya&lt;/option&gt;
        &lt;option value=&quot;Kiribati&quot;&gt;Kiribati&lt;/option&gt;
        &lt;option value=&quot;Korea North&quot;&gt;Korea North&lt;/option&gt;
        &lt;option value=&quot;Korea South&quot;&gt;Korea South&lt;/option&gt;
        &lt;option value=&quot;Kosovo&quot;&gt;Kosovo&lt;/option&gt;
        &lt;option value=&quot;Kuwait&quot;&gt;Kuwait&lt;/option&gt;
        &lt;option value=&quot;Kyrgyzstan&quot;&gt;Kyrgyzstan&lt;/option&gt;
        &lt;option value=&quot;Laos&quot;&gt;Laos&lt;/option&gt;
        &lt;option value=&quot;Latvia&quot;&gt;Latvia&lt;/option&gt;
        &lt;option value=&quot;Lebanon&quot;&gt;Lebanon&lt;/option&gt;
        &lt;option value=&quot;Lesotho&quot;&gt;Lesotho&lt;/option&gt;
        &lt;option value=&quot;Liberia&quot;&gt;Liberia&lt;/option&gt;
        &lt;option value=&quot;Libya&quot;&gt;Libya&lt;/option&gt;
        &lt;option value=&quot;Liechtenstein&quot;&gt;Liechtenstein&lt;/option&gt;
        &lt;option value=&quot;Lithuania&quot;&gt;Lithuania&lt;/option&gt;
        &lt;option value=&quot;Luxembourg&quot;&gt;Luxembourg&lt;/option&gt;
        &lt;option value=&quot;Macedonia&quot;&gt;Macedonia&lt;/option&gt;
        &lt;option value=&quot;Madagascar&quot;&gt;Madagascar&lt;/option&gt;
        &lt;option value=&quot;Malawi&quot;&gt;Malawi&lt;/option&gt;
        &lt;option value=&quot;Malaysia&quot;&gt;Malaysia&lt;/option&gt;
        &lt;option value=&quot;Maldives&quot;&gt;Maldives&lt;/option&gt;
        &lt;option value=&quot;Mali&quot;&gt;Mali&lt;/option&gt;
        &lt;option value=&quot;Malta&quot;&gt;Malta&lt;/option&gt;
        &lt;option value=&quot;Marshall Islands&quot;&gt;Marshall Islands&lt;/option&gt;
        &lt;option value=&quot;Mauritania&quot;&gt;Mauritania&lt;/option&gt;
        &lt;option value=&quot;Mauritius&quot;&gt;Mauritius&lt;/option&gt;
        &lt;option value=&quot;Mexico&quot;&gt;Mexico&lt;/option&gt;
        &lt;option value=&quot;Micronesia&quot;&gt;Micronesia&lt;/option&gt;
        &lt;option value=&quot;Moldova&quot;&gt;Moldova&lt;/option&gt;
        &lt;option value=&quot;Monaco&quot;&gt;Monaco&lt;/option&gt;
        &lt;option value=&quot;Mongolia&quot;&gt;Mongolia&lt;/option&gt;
        &lt;option value=&quot;Montenegro&quot;&gt;Montenegro&lt;/option&gt;
        &lt;option value=&quot;Morocco&quot;&gt;Morocco&lt;/option&gt;
        &lt;option value=&quot;Mozambique&quot;&gt;Mozambique&lt;/option&gt;
        &lt;option value=&quot;Myanmar, {Burma}&quot;&gt;Myanmar, {Burma}&lt;/option&gt;
        &lt;option value=&quot;Namibia&quot;&gt;Namibia&lt;/option&gt;
        &lt;option value=&quot;Nauru&quot;&gt;Nauru&lt;/option&gt;
        &lt;option value=&quot;Nepal&quot;&gt;Nepal&lt;/option&gt;
        &lt;option value=&quot;Netherlands&quot;&gt;Netherlands&lt;/option&gt;
        &lt;option value=&quot;New Zealand&quot;&gt;New Zealand&lt;/option&gt;
        &lt;option value=&quot;Nicaragua&quot;&gt;Nicaragua&lt;/option&gt;
        &lt;option value=&quot;Niger&quot;&gt;Niger&lt;/option&gt;
        &lt;option value=&quot;Nigeria&quot;&gt;Nigeria&lt;/option&gt;
        &lt;option value=&quot;Norway&quot;&gt;Norway&lt;/option&gt;
        &lt;option value=&quot;Oman&quot;&gt;Oman&lt;/option&gt;
        &lt;option value=&quot;Pakistan&quot;&gt;Pakistan&lt;/option&gt;
        &lt;option value=&quot;Palau&quot;&gt;Palau&lt;/option&gt;
        &lt;option value=&quot;Panama&quot;&gt;Panama&lt;/option&gt;
        &lt;option value=&quot;Papua New Guinea&quot;&gt;Papua New Guinea&lt;/option&gt;
        &lt;option value=&quot;Paraguay&quot;&gt;Paraguay&lt;/option&gt;
        &lt;option value=&quot;Peru&quot;&gt;Peru&lt;/option&gt;
        &lt;option value=&quot;Philippines&quot;&gt;Philippines&lt;/option&gt;
        &lt;option value=&quot;Poland&quot;&gt;Poland&lt;/option&gt;
        &lt;option value=&quot;Portugal&quot;&gt;Portugal&lt;/option&gt;
        &lt;option value=&quot;Qatar&quot;&gt;Qatar&lt;/option&gt;
        &lt;option value=&quot;Romania&quot;&gt;Romania&lt;/option&gt;
        &lt;option value=&quot;Russian Federation&quot;&gt;Russian Federation&lt;/option&gt;
        &lt;option value=&quot;Rwanda&quot;&gt;Rwanda&lt;/option&gt;
        &lt;option value=&quot;St Kitts &amp;amp; Nevis&quot;&gt;St Kitts &amp;amp; Nevis&lt;/option&gt;
        &lt;option value=&quot;St Lucia&quot;&gt;St Lucia&lt;/option&gt;
        &lt;option value=&quot;Saint Vincent &amp;amp; the Grenadines&quot;&gt;Saint Vincent &amp;amp; the Grenadines&lt;/option&gt;
        &lt;option value=&quot;Samoa&quot;&gt;Samoa&lt;/option&gt;
        &lt;option value=&quot;San Marino&quot;&gt;San Marino&lt;/option&gt;
        &lt;option value=&quot;Sao Tome &amp;amp; Principe&quot;&gt;Sao Tome &amp;amp; Principe&lt;/option&gt;
        &lt;option value=&quot;Saudi Arabia&quot;&gt;Saudi Arabia&lt;/option&gt;
        &lt;option value=&quot;Senegal&quot;&gt;Senegal&lt;/option&gt;
        &lt;option value=&quot;Serbia&quot;&gt;Serbia&lt;/option&gt;
        &lt;option value=&quot;Seychelles&quot;&gt;Seychelles&lt;/option&gt;
        &lt;option value=&quot;Sierra Leone&quot;&gt;Sierra Leone&lt;/option&gt;
        &lt;option value=&quot;Singapore&quot;&gt;Singapore&lt;/option&gt;
        &lt;option value=&quot;Slovakia&quot;&gt;Slovakia&lt;/option&gt;
        &lt;option value=&quot;Slovenia&quot;&gt;Slovenia&lt;/option&gt;
        &lt;option value=&quot;Solomon Islands&quot;&gt;Solomon Islands&lt;/option&gt;
        &lt;option value=&quot;Somalia&quot;&gt;Somalia&lt;/option&gt;
        &lt;option value=&quot;South Africa&quot;&gt;South Africa&lt;/option&gt;
        &lt;option value=&quot;South Sudan&quot;&gt;South Sudan&lt;/option&gt;
        &lt;option value=&quot;Spain&quot;&gt;Spain&lt;/option&gt;
        &lt;option value=&quot;Sri Lanka&quot;&gt;Sri Lanka&lt;/option&gt;
        &lt;option value=&quot;Sudan&quot;&gt;Sudan&lt;/option&gt;
        &lt;option value=&quot;Suriname&quot;&gt;Suriname&lt;/option&gt;
        &lt;option value=&quot;Swaziland&quot;&gt;Swaziland&lt;/option&gt;
        &lt;option value=&quot;Sweden&quot;&gt;Sweden&lt;/option&gt;
        &lt;option value=&quot;Switzerland&quot;&gt;Switzerland&lt;/option&gt;
        &lt;option value=&quot;Syria&quot;&gt;Syria&lt;/option&gt;
        &lt;option value=&quot;Taiwan&quot;&gt;Taiwan&lt;/option&gt;
        &lt;option value=&quot;Tajikistan&quot;&gt;Tajikistan&lt;/option&gt;
        &lt;option value=&quot;Tanzania&quot;&gt;Tanzania&lt;/option&gt;
        &lt;option value=&quot;Thailand&quot;&gt;Thailand&lt;/option&gt;
        &lt;option value=&quot;Togo&quot;&gt;Togo&lt;/option&gt;
        &lt;option value=&quot;Tonga&quot;&gt;Tonga&lt;/option&gt;
        &lt;option value=&quot;Trinidad &amp;amp; Tobago&quot;&gt;Trinidad &amp;amp; Tobago&lt;/option&gt;
        &lt;option value=&quot;Tunisia&quot;&gt;Tunisia&lt;/option&gt;
        &lt;option value=&quot;Turkey&quot;&gt;Turkey&lt;/option&gt;
        &lt;option value=&quot;Turkmenistan&quot;&gt;Turkmenistan&lt;/option&gt;
        &lt;option value=&quot;Tuvalu&quot;&gt;Tuvalu&lt;/option&gt;
        &lt;option value=&quot;Uganda&quot;&gt;Uganda&lt;/option&gt;
        &lt;option value=&quot;Ukraine&quot;&gt;Ukraine&lt;/option&gt;
        &lt;option value=&quot;United Arab Emirates&quot;&gt;United Arab Emirates&lt;/option&gt;
        &lt;option value=&quot;Uruguay&quot;&gt;Uruguay&lt;/option&gt;
        &lt;option value=&quot;Uzbekistan&quot;&gt;Uzbekistan&lt;/option&gt;
        &lt;option value=&quot;Vanuatu&quot;&gt;Vanuatu&lt;/option&gt;
        &lt;option value=&quot;Vatican City&quot;&gt;Vatican City&lt;/option&gt;
        &lt;option value=&quot;Venezuela&quot;&gt;Venezuela&lt;/option&gt;
        &lt;option value=&quot;Vietnam&quot;&gt;Vietnam&lt;/option&gt;
        &lt;option value=&quot;Yemen&quot;&gt;Yemen&lt;/option&gt;
        &lt;option value=&quot;Zambia&quot;&gt;Zambia&lt;/option&gt;
        &lt;option value=&quot;Zimbabwe&quot;&gt;Zimbabwe&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_4&quot;&gt;Web Site&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_4&quot; name=&quot;text_4&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Email Address&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_5&quot;&gt;Home Phone&lt;/label&gt;
    &lt;input type=&quot;tel&quot; id=&quot;text_5&quot; name=&quot;text_5&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_6&quot;&gt;Cell Phone&lt;/label&gt;
    &lt;input type=&quot;tel&quot; id=&quot;text_6&quot; name=&quot;text_6&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Date --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;date_0&quot;&gt;Birthday&lt;/label&gt;
    &lt;input type=&quot;date&quot; id=&quot;date_0&quot; name=&quot;date_0&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Notes&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'address-book',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 10,
            'category_id' => 3,
            'name' => 'Sales Lead Form',
            'description' => 'Capture your website leads with a simple lead form. Our sales lead form helps you understand where your leads are coming from, and it asks for detailed contact information so that you can reach out to prospects',
            'builder' => '{"settings":{"name":"Sales Lead Form","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Sales Lead Form","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"type"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"Please enter your information below so we can be in touch.","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Company","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Title at Company","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Contact Email","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_3","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":false,"label":"Text"},{"value":"tel","selected":true,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Contact Phone","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_4","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Contact Address","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_5","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"City","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_6","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"State","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Country","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["United States","United Kingdom","Australia","Canada","France","----","Afghanistan","Albania","Algeria","Andorra","Angola","Antigua \u0026 Deps","Argentina","Armenia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina","Burundi","Cambodia","Cameroon","Cape Verde","Central African Rep","Chad","Chile","China","Colombia","Comoros","Congo","Congo {Democratic Rep}","Costa Rica","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","East Timor","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Fiji","Finland","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland {Republic}","Israel","Italy","Ivory Coast","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea North","Korea South","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar, {Burma}","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russian Federation","Rwanda","St Kitts \u0026 Nevis","St Lucia","Saint Vincent \u0026 the Grenadines","Samoa","San Marino","Sao Tome \u0026 Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Togo","Tonga","Trinidad \u0026 Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-4 no-padding-right","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_7","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":false,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":true,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Company Website","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Please tell us about your industry.","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Questions or Comments","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":876}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Sales Lead Form&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;Please enter your information below so we can be in touch.&lt;/p&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Company&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;Title at Company&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Contact Email&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; value=&quot;&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_3&quot;&gt;Contact Phone&lt;/label&gt;
    &lt;input type=&quot;tel&quot; id=&quot;text_3&quot; name=&quot;text_3&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_4&quot;&gt;Contact Address&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_4&quot; name=&quot;text_4&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_5&quot;&gt;City&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_5&quot; name=&quot;text_5&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_6&quot;&gt;State&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_6&quot; name=&quot;text_6&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-4 no-padding-right&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Country&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
        &lt;option value=&quot;United Kingdom&quot;&gt;United Kingdom&lt;/option&gt;
        &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
        &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
        &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
        &lt;option value=&quot;----&quot;&gt;----&lt;/option&gt;
        &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
        &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
        &lt;option value=&quot;Algeria&quot;&gt;Algeria&lt;/option&gt;
        &lt;option value=&quot;Andorra&quot;&gt;Andorra&lt;/option&gt;
        &lt;option value=&quot;Angola&quot;&gt;Angola&lt;/option&gt;
        &lt;option value=&quot;Antigua &amp;amp; Deps&quot;&gt;Antigua &amp;amp; Deps&lt;/option&gt;
        &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
        &lt;option value=&quot;Armenia&quot;&gt;Armenia&lt;/option&gt;
        &lt;option value=&quot;Austria&quot;&gt;Austria&lt;/option&gt;
        &lt;option value=&quot;Azerbaijan&quot;&gt;Azerbaijan&lt;/option&gt;
        &lt;option value=&quot;Bahamas&quot;&gt;Bahamas&lt;/option&gt;
        &lt;option value=&quot;Bahrain&quot;&gt;Bahrain&lt;/option&gt;
        &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
        &lt;option value=&quot;Barbados&quot;&gt;Barbados&lt;/option&gt;
        &lt;option value=&quot;Belarus&quot;&gt;Belarus&lt;/option&gt;
        &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
        &lt;option value=&quot;Belize&quot;&gt;Belize&lt;/option&gt;
        &lt;option value=&quot;Benin&quot;&gt;Benin&lt;/option&gt;
        &lt;option value=&quot;Bhutan&quot;&gt;Bhutan&lt;/option&gt;
        &lt;option value=&quot;Bolivia&quot;&gt;Bolivia&lt;/option&gt;
        &lt;option value=&quot;Bosnia Herzegovina&quot;&gt;Bosnia Herzegovina&lt;/option&gt;
        &lt;option value=&quot;Botswana&quot;&gt;Botswana&lt;/option&gt;
        &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
        &lt;option value=&quot;Brunei&quot;&gt;Brunei&lt;/option&gt;
        &lt;option value=&quot;Bulgaria&quot;&gt;Bulgaria&lt;/option&gt;
        &lt;option value=&quot;Burkina&quot;&gt;Burkina&lt;/option&gt;
        &lt;option value=&quot;Burundi&quot;&gt;Burundi&lt;/option&gt;
        &lt;option value=&quot;Cambodia&quot;&gt;Cambodia&lt;/option&gt;
        &lt;option value=&quot;Cameroon&quot;&gt;Cameroon&lt;/option&gt;
        &lt;option value=&quot;Cape Verde&quot;&gt;Cape Verde&lt;/option&gt;
        &lt;option value=&quot;Central African Rep&quot;&gt;Central African Rep&lt;/option&gt;
        &lt;option value=&quot;Chad&quot;&gt;Chad&lt;/option&gt;
        &lt;option value=&quot;Chile&quot;&gt;Chile&lt;/option&gt;
        &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
        &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
        &lt;option value=&quot;Comoros&quot;&gt;Comoros&lt;/option&gt;
        &lt;option value=&quot;Congo&quot;&gt;Congo&lt;/option&gt;
        &lt;option value=&quot;Congo {Democratic Rep}&quot;&gt;Congo {Democratic Rep}&lt;/option&gt;
        &lt;option value=&quot;Costa Rica&quot;&gt;Costa Rica&lt;/option&gt;
        &lt;option value=&quot;Croatia&quot;&gt;Croatia&lt;/option&gt;
        &lt;option value=&quot;Cuba&quot;&gt;Cuba&lt;/option&gt;
        &lt;option value=&quot;Cyprus&quot;&gt;Cyprus&lt;/option&gt;
        &lt;option value=&quot;Czech Republic&quot;&gt;Czech Republic&lt;/option&gt;
        &lt;option value=&quot;Denmark&quot;&gt;Denmark&lt;/option&gt;
        &lt;option value=&quot;Djibouti&quot;&gt;Djibouti&lt;/option&gt;
        &lt;option value=&quot;Dominica&quot;&gt;Dominica&lt;/option&gt;
        &lt;option value=&quot;Dominican Republic&quot;&gt;Dominican Republic&lt;/option&gt;
        &lt;option value=&quot;East Timor&quot;&gt;East Timor&lt;/option&gt;
        &lt;option value=&quot;Ecuador&quot;&gt;Ecuador&lt;/option&gt;
        &lt;option value=&quot;Egypt&quot;&gt;Egypt&lt;/option&gt;
        &lt;option value=&quot;El Salvador&quot;&gt;El Salvador&lt;/option&gt;
        &lt;option value=&quot;Equatorial Guinea&quot;&gt;Equatorial Guinea&lt;/option&gt;
        &lt;option value=&quot;Eritrea&quot;&gt;Eritrea&lt;/option&gt;
        &lt;option value=&quot;Estonia&quot;&gt;Estonia&lt;/option&gt;
        &lt;option value=&quot;Ethiopia&quot;&gt;Ethiopia&lt;/option&gt;
        &lt;option value=&quot;Fiji&quot;&gt;Fiji&lt;/option&gt;
        &lt;option value=&quot;Finland&quot;&gt;Finland&lt;/option&gt;
        &lt;option value=&quot;Gabon&quot;&gt;Gabon&lt;/option&gt;
        &lt;option value=&quot;Gambia&quot;&gt;Gambia&lt;/option&gt;
        &lt;option value=&quot;Georgia&quot;&gt;Georgia&lt;/option&gt;
        &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
        &lt;option value=&quot;Ghana&quot;&gt;Ghana&lt;/option&gt;
        &lt;option value=&quot;Greece&quot;&gt;Greece&lt;/option&gt;
        &lt;option value=&quot;Grenada&quot;&gt;Grenada&lt;/option&gt;
        &lt;option value=&quot;Guatemala&quot;&gt;Guatemala&lt;/option&gt;
        &lt;option value=&quot;Guinea&quot;&gt;Guinea&lt;/option&gt;
        &lt;option value=&quot;Guinea-Bissau&quot;&gt;Guinea-Bissau&lt;/option&gt;
        &lt;option value=&quot;Guyana&quot;&gt;Guyana&lt;/option&gt;
        &lt;option value=&quot;Haiti&quot;&gt;Haiti&lt;/option&gt;
        &lt;option value=&quot;Honduras&quot;&gt;Honduras&lt;/option&gt;
        &lt;option value=&quot;Hungary&quot;&gt;Hungary&lt;/option&gt;
        &lt;option value=&quot;Iceland&quot;&gt;Iceland&lt;/option&gt;
        &lt;option value=&quot;India&quot;&gt;India&lt;/option&gt;
        &lt;option value=&quot;Indonesia&quot;&gt;Indonesia&lt;/option&gt;
        &lt;option value=&quot;Iran&quot;&gt;Iran&lt;/option&gt;
        &lt;option value=&quot;Iraq&quot;&gt;Iraq&lt;/option&gt;
        &lt;option value=&quot;Ireland {Republic}&quot;&gt;Ireland {Republic}&lt;/option&gt;
        &lt;option value=&quot;Israel&quot;&gt;Israel&lt;/option&gt;
        &lt;option value=&quot;Italy&quot;&gt;Italy&lt;/option&gt;
        &lt;option value=&quot;Ivory Coast&quot;&gt;Ivory Coast&lt;/option&gt;
        &lt;option value=&quot;Jamaica&quot;&gt;Jamaica&lt;/option&gt;
        &lt;option value=&quot;Japan&quot;&gt;Japan&lt;/option&gt;
        &lt;option value=&quot;Jordan&quot;&gt;Jordan&lt;/option&gt;
        &lt;option value=&quot;Kazakhstan&quot;&gt;Kazakhstan&lt;/option&gt;
        &lt;option value=&quot;Kenya&quot;&gt;Kenya&lt;/option&gt;
        &lt;option value=&quot;Kiribati&quot;&gt;Kiribati&lt;/option&gt;
        &lt;option value=&quot;Korea North&quot;&gt;Korea North&lt;/option&gt;
        &lt;option value=&quot;Korea South&quot;&gt;Korea South&lt;/option&gt;
        &lt;option value=&quot;Kosovo&quot;&gt;Kosovo&lt;/option&gt;
        &lt;option value=&quot;Kuwait&quot;&gt;Kuwait&lt;/option&gt;
        &lt;option value=&quot;Kyrgyzstan&quot;&gt;Kyrgyzstan&lt;/option&gt;
        &lt;option value=&quot;Laos&quot;&gt;Laos&lt;/option&gt;
        &lt;option value=&quot;Latvia&quot;&gt;Latvia&lt;/option&gt;
        &lt;option value=&quot;Lebanon&quot;&gt;Lebanon&lt;/option&gt;
        &lt;option value=&quot;Lesotho&quot;&gt;Lesotho&lt;/option&gt;
        &lt;option value=&quot;Liberia&quot;&gt;Liberia&lt;/option&gt;
        &lt;option value=&quot;Libya&quot;&gt;Libya&lt;/option&gt;
        &lt;option value=&quot;Liechtenstein&quot;&gt;Liechtenstein&lt;/option&gt;
        &lt;option value=&quot;Lithuania&quot;&gt;Lithuania&lt;/option&gt;
        &lt;option value=&quot;Luxembourg&quot;&gt;Luxembourg&lt;/option&gt;
        &lt;option value=&quot;Macedonia&quot;&gt;Macedonia&lt;/option&gt;
        &lt;option value=&quot;Madagascar&quot;&gt;Madagascar&lt;/option&gt;
        &lt;option value=&quot;Malawi&quot;&gt;Malawi&lt;/option&gt;
        &lt;option value=&quot;Malaysia&quot;&gt;Malaysia&lt;/option&gt;
        &lt;option value=&quot;Maldives&quot;&gt;Maldives&lt;/option&gt;
        &lt;option value=&quot;Mali&quot;&gt;Mali&lt;/option&gt;
        &lt;option value=&quot;Malta&quot;&gt;Malta&lt;/option&gt;
        &lt;option value=&quot;Marshall Islands&quot;&gt;Marshall Islands&lt;/option&gt;
        &lt;option value=&quot;Mauritania&quot;&gt;Mauritania&lt;/option&gt;
        &lt;option value=&quot;Mauritius&quot;&gt;Mauritius&lt;/option&gt;
        &lt;option value=&quot;Mexico&quot;&gt;Mexico&lt;/option&gt;
        &lt;option value=&quot;Micronesia&quot;&gt;Micronesia&lt;/option&gt;
        &lt;option value=&quot;Moldova&quot;&gt;Moldova&lt;/option&gt;
        &lt;option value=&quot;Monaco&quot;&gt;Monaco&lt;/option&gt;
        &lt;option value=&quot;Mongolia&quot;&gt;Mongolia&lt;/option&gt;
        &lt;option value=&quot;Montenegro&quot;&gt;Montenegro&lt;/option&gt;
        &lt;option value=&quot;Morocco&quot;&gt;Morocco&lt;/option&gt;
        &lt;option value=&quot;Mozambique&quot;&gt;Mozambique&lt;/option&gt;
        &lt;option value=&quot;Myanmar, {Burma}&quot;&gt;Myanmar, {Burma}&lt;/option&gt;
        &lt;option value=&quot;Namibia&quot;&gt;Namibia&lt;/option&gt;
        &lt;option value=&quot;Nauru&quot;&gt;Nauru&lt;/option&gt;
        &lt;option value=&quot;Nepal&quot;&gt;Nepal&lt;/option&gt;
        &lt;option value=&quot;Netherlands&quot;&gt;Netherlands&lt;/option&gt;
        &lt;option value=&quot;New Zealand&quot;&gt;New Zealand&lt;/option&gt;
        &lt;option value=&quot;Nicaragua&quot;&gt;Nicaragua&lt;/option&gt;
        &lt;option value=&quot;Niger&quot;&gt;Niger&lt;/option&gt;
        &lt;option value=&quot;Nigeria&quot;&gt;Nigeria&lt;/option&gt;
        &lt;option value=&quot;Norway&quot;&gt;Norway&lt;/option&gt;
        &lt;option value=&quot;Oman&quot;&gt;Oman&lt;/option&gt;
        &lt;option value=&quot;Pakistan&quot;&gt;Pakistan&lt;/option&gt;
        &lt;option value=&quot;Palau&quot;&gt;Palau&lt;/option&gt;
        &lt;option value=&quot;Panama&quot;&gt;Panama&lt;/option&gt;
        &lt;option value=&quot;Papua New Guinea&quot;&gt;Papua New Guinea&lt;/option&gt;
        &lt;option value=&quot;Paraguay&quot;&gt;Paraguay&lt;/option&gt;
        &lt;option value=&quot;Peru&quot;&gt;Peru&lt;/option&gt;
        &lt;option value=&quot;Philippines&quot;&gt;Philippines&lt;/option&gt;
        &lt;option value=&quot;Poland&quot;&gt;Poland&lt;/option&gt;
        &lt;option value=&quot;Portugal&quot;&gt;Portugal&lt;/option&gt;
        &lt;option value=&quot;Qatar&quot;&gt;Qatar&lt;/option&gt;
        &lt;option value=&quot;Romania&quot;&gt;Romania&lt;/option&gt;
        &lt;option value=&quot;Russian Federation&quot;&gt;Russian Federation&lt;/option&gt;
        &lt;option value=&quot;Rwanda&quot;&gt;Rwanda&lt;/option&gt;
        &lt;option value=&quot;St Kitts &amp;amp; Nevis&quot;&gt;St Kitts &amp;amp; Nevis&lt;/option&gt;
        &lt;option value=&quot;St Lucia&quot;&gt;St Lucia&lt;/option&gt;
        &lt;option value=&quot;Saint Vincent &amp;amp; the Grenadines&quot;&gt;Saint Vincent &amp;amp; the Grenadines&lt;/option&gt;
        &lt;option value=&quot;Samoa&quot;&gt;Samoa&lt;/option&gt;
        &lt;option value=&quot;San Marino&quot;&gt;San Marino&lt;/option&gt;
        &lt;option value=&quot;Sao Tome &amp;amp; Principe&quot;&gt;Sao Tome &amp;amp; Principe&lt;/option&gt;
        &lt;option value=&quot;Saudi Arabia&quot;&gt;Saudi Arabia&lt;/option&gt;
        &lt;option value=&quot;Senegal&quot;&gt;Senegal&lt;/option&gt;
        &lt;option value=&quot;Serbia&quot;&gt;Serbia&lt;/option&gt;
        &lt;option value=&quot;Seychelles&quot;&gt;Seychelles&lt;/option&gt;
        &lt;option value=&quot;Sierra Leone&quot;&gt;Sierra Leone&lt;/option&gt;
        &lt;option value=&quot;Singapore&quot;&gt;Singapore&lt;/option&gt;
        &lt;option value=&quot;Slovakia&quot;&gt;Slovakia&lt;/option&gt;
        &lt;option value=&quot;Slovenia&quot;&gt;Slovenia&lt;/option&gt;
        &lt;option value=&quot;Solomon Islands&quot;&gt;Solomon Islands&lt;/option&gt;
        &lt;option value=&quot;Somalia&quot;&gt;Somalia&lt;/option&gt;
        &lt;option value=&quot;South Africa&quot;&gt;South Africa&lt;/option&gt;
        &lt;option value=&quot;South Sudan&quot;&gt;South Sudan&lt;/option&gt;
        &lt;option value=&quot;Spain&quot;&gt;Spain&lt;/option&gt;
        &lt;option value=&quot;Sri Lanka&quot;&gt;Sri Lanka&lt;/option&gt;
        &lt;option value=&quot;Sudan&quot;&gt;Sudan&lt;/option&gt;
        &lt;option value=&quot;Suriname&quot;&gt;Suriname&lt;/option&gt;
        &lt;option value=&quot;Swaziland&quot;&gt;Swaziland&lt;/option&gt;
        &lt;option value=&quot;Sweden&quot;&gt;Sweden&lt;/option&gt;
        &lt;option value=&quot;Switzerland&quot;&gt;Switzerland&lt;/option&gt;
        &lt;option value=&quot;Syria&quot;&gt;Syria&lt;/option&gt;
        &lt;option value=&quot;Taiwan&quot;&gt;Taiwan&lt;/option&gt;
        &lt;option value=&quot;Tajikistan&quot;&gt;Tajikistan&lt;/option&gt;
        &lt;option value=&quot;Tanzania&quot;&gt;Tanzania&lt;/option&gt;
        &lt;option value=&quot;Thailand&quot;&gt;Thailand&lt;/option&gt;
        &lt;option value=&quot;Togo&quot;&gt;Togo&lt;/option&gt;
        &lt;option value=&quot;Tonga&quot;&gt;Tonga&lt;/option&gt;
        &lt;option value=&quot;Trinidad &amp;amp; Tobago&quot;&gt;Trinidad &amp;amp; Tobago&lt;/option&gt;
        &lt;option value=&quot;Tunisia&quot;&gt;Tunisia&lt;/option&gt;
        &lt;option value=&quot;Turkey&quot;&gt;Turkey&lt;/option&gt;
        &lt;option value=&quot;Turkmenistan&quot;&gt;Turkmenistan&lt;/option&gt;
        &lt;option value=&quot;Tuvalu&quot;&gt;Tuvalu&lt;/option&gt;
        &lt;option value=&quot;Uganda&quot;&gt;Uganda&lt;/option&gt;
        &lt;option value=&quot;Ukraine&quot;&gt;Ukraine&lt;/option&gt;
        &lt;option value=&quot;United Arab Emirates&quot;&gt;United Arab Emirates&lt;/option&gt;
        &lt;option value=&quot;Uruguay&quot;&gt;Uruguay&lt;/option&gt;
        &lt;option value=&quot;Uzbekistan&quot;&gt;Uzbekistan&lt;/option&gt;
        &lt;option value=&quot;Vanuatu&quot;&gt;Vanuatu&lt;/option&gt;
        &lt;option value=&quot;Vatican City&quot;&gt;Vatican City&lt;/option&gt;
        &lt;option value=&quot;Venezuela&quot;&gt;Venezuela&lt;/option&gt;
        &lt;option value=&quot;Vietnam&quot;&gt;Vietnam&lt;/option&gt;
        &lt;option value=&quot;Yemen&quot;&gt;Yemen&lt;/option&gt;
        &lt;option value=&quot;Zambia&quot;&gt;Zambia&lt;/option&gt;
        &lt;option value=&quot;Zimbabwe&quot;&gt;Zimbabwe&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_7&quot;&gt;Company Website&lt;/label&gt;
    &lt;input type=&quot;url&quot; id=&quot;text_7&quot; name=&quot;text_7&quot; value=&quot;&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Please tell us about your industry.&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_1&quot;&gt;Questions or Comments&lt;/label&gt;
    &lt;textarea id=&quot;textarea_1&quot; name=&quot;textarea_1&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 1,
            'slug' => 'sales-lead-form',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 11,
            'category_id' => 1,
            'name' => 'Mailing List',
            'description' => 'A mailing list is a collection of names and addresses used by an individual or an organization to send material to multiple recipients.',
            'builder' => '{"settings":{"name":"Mailing List","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"form-inline","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Join our Mailing List","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"heading"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"Keep up to date with information about our publishing by joining our mailing list!","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Your Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"Your Name","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"email","title":"email.title","fields":{"id":{"label":"component.id","type":"input","value":"email_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Your Email Address","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"Your Email","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"checkdns":{"label":"component.checkDNS","type":"checkbox","value":false,"advanced":true,"name":"checkdns"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":150}',
            'html' => '&lt;form id=&quot;form-app&quot; class=&quot;form-inline&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Join our Mailing List&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;Keep up to date with information about our publishing by joining our mailing list!&lt;/p&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;Your Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; placeholder=&quot;Your Name&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Email --&gt;
&lt;div class=&quot;form-group required-control&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;email_0&quot;&gt;Your Email Address&lt;/label&gt;
    &lt;input type=&quot;email&quot; id=&quot;email_0&quot; name=&quot;email_0&quot; placeholder=&quot;Your Email&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'mailing-list',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

        $this->insert('{{%template}}', [
            'id' => 12,
            'category_id' => 4,
            'name' => 'Wedding RSVP Form',
            'description' => 'About to get married? Create your own online RSVP to know how many guests would attend your wedding reception. Easy for your guests to read and quickly to fill in!',
            'builder' => '{"settings":{"name":"Wedding RSVP Form","canvas":"#canvas","disabledFieldset":false,"layoutSelected":"","layouts":[{"id":"","name":"Vertical"},{"id":"form-horizontal","name":"Horizontal"},{"id":"form-inline","name":"Inline"}],"formSteps":{"title":"formSteps.title","fields":{"id":{"label":"formSteps.id","type":"input","value":"formSteps","name":"id"},"steps":{"label":"formSteps.steps","type":"textarea-split","value":[],"name":"steps"},"progressBar":{"label":"formSteps.progressBar","type":"checkbox","value":false,"name":"progressBar"},"noTitles":{"label":"formSteps.noTitles","type":"checkbox","value":false,"name":"noTitles"},"noStages":{"label":"formSteps.noStages","type":"checkbox","value":false,"name":"noStages"},"noSteps":{"label":"formSteps.noSteps","type":"checkbox","value":false,"name":"noSteps"}}}},"initForm":[{"name":"heading","title":"heading.title","fields":{"id":{"label":"component.id","type":"input","value":"heading_0","name":"id"},"text":{"label":"component.text","type":"input","value":"Caroline & Steven\'s Wedding RSVP","name":"text"},"type":{"label":"component.type","type":"select","value":[{"value":"h1","selected":false,"label":"H1"},{"value":"h2","selected":false,"label":"H2"},{"value":"h3","selected":true,"label":"H3"},{"value":"h4","selected":false,"label":"H4"},{"value":"h5","selected":false,"label":"H5"},{"value":"h6","selected":false,"label":"H6"}],"name":"type"},"cssClass":{"label":"component.cssClass","type":"input","value":"legend","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"paragraph","title":"paragraph.title","fields":{"id":{"label":"component.id","type":"input","value":"paragraph_0","name":"id"},"text":{"label":"component.text","type":"textarea","value":"We look forward to celebrating with you! Please reply by October 18, 2016","name":"text"},"cssClass":{"label":"component.cssClass","type":"input","value":"","advanced":true,"name":"cssClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_0","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"First Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_1","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Last Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Are you coming?","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["Can\'t Wait!","Sorry To Miss Out"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":true,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding-left","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"selectlist","title":"selectlist.title","fields":{"id":{"label":"component.id","type":"input","value":"selectlist_1","name":"id"},"label":{"label":"component.label","type":"input","value":"Persons will atend","name":"label"},"options":{"label":"component.options","type":"textarea-split","value":["1","2"],"name":"options"},"placeholder":{"label":"component.placeholder","type":"input","value":"-Select-","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"col-sm-6 no-padding","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"},"multiple":{"label":"component.multiple","type":"checkbox","value":false,"advanced":true,"name":"multiple"}},"fresh":false},{"name":"text","title":"text.title","fields":{"id":{"label":"component.id","type":"input","value":"text_2","name":"id"},"inputType":{"label":"component.inputType","type":"select","value":[{"value":"text","selected":true,"label":"Text"},{"value":"tel","selected":false,"label":"Tel"},{"value":"url","selected":false,"label":"URL"},{"value":"color","selected":false,"label":"Color"},{"value":"password","selected":false,"label":"Password"}],"name":"inputType"},"label":{"label":"component.label","type":"input","value":"Guest Name","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"predefinedValue":{"label":"component.predefinedValue","type":"input","value":"","advanced":true,"name":"predefinedValue"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"pattern":{"label":"component.pattern","type":"input","value":"","advanced":true,"name":"pattern"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"textarea","title":"textarea.title","fields":{"id":{"label":"component.id","type":"input","value":"textarea_0","name":"id"},"label":{"label":"component.label","type":"input","value":"Any special dietary requirements","name":"label"},"placeholder":{"label":"component.placeholder","type":"input","value":"","name":"placeholder"},"predefinedValue":{"label":"component.predefinedValue","type":"textarea","value":"","advanced":true,"name":"predefinedValue"},"required":{"label":"component.required","type":"checkbox","value":false,"name":"required"},"helpText":{"label":"component.helpText","type":"textarea","value":"","advanced":true,"name":"helpText"},"fieldSize":{"label":"component.fieldSize","type":"input","value":"3","advanced":true,"name":"fieldSize"},"cssClass":{"label":"component.cssClass","type":"input","value":"form-control","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"unique":{"label":"component.unique","type":"checkbox","value":false,"advanced":true,"name":"unique"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false},{"name":"snippet","title":"snippet.title","fields":{"id":{"label":"component.id","type":"input","value":"snippet_0","name":"id"},"snippet":{"label":"component.htmlCode","type":"textarea","value":"\u003Cp\u003E\u003Cstrong\u003EHow to get there!\u003C\/strong\u003E\u003C\/p\u003E\n\u003Cp\u003E\u003Csmall\u003EThe Francis Marion Hotel 387 King Street Charleston. South Carolina.\u003C\/small\u003E\u003C\/p\u003E\n\u003Cdiv id=\u0022map\u0022\u003E\u003C\/div\u003E\n\u003Cstyle type=\u0022text\/css\u0022\u003E\n#map {\n    width: 100%;\n    height: 300px;\n    margin-bottom: 15px;\n    background-color: #f3f5f7;\n}\n\u003C\/style\u003E","name":"snippet"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"}},"fresh":false},{"name":"button","title":"button.title","fields":{"id":{"label":"component.id","type":"input","value":"button_0","name":"id"},"inputType":{"label":"component.type","type":"select","value":[{"value":"submit","label":"Submit","selected":true},{"value":"reset","label":"Reset","selected":false},{"value":"image","label":"Image","selected":false}],"name":"inputType"},"buttonText":{"label":"component.buttonText","type":"input","value":"Submit","name":"buttonText"},"label":{"label":"component.label","type":"input","value":"","advanced":true,"name":"label"},"src":{"label":"component.src","type":"input","value":"","advanced":true,"name":"src"},"cssClass":{"label":"component.cssClass","type":"input","value":"btn btn-primary","advanced":true,"name":"cssClass"},"labelClass":{"label":"component.labelClass","type":"input","value":"control-label","advanced":true,"name":"labelClass"},"containerClass":{"label":"component.containerClass","type":"input","value":"","advanced":true,"name":"containerClass"},"readOnly":{"label":"component.readOnly","type":"checkbox","value":false,"advanced":true,"name":"readOnly"},"disabled":{"label":"component.disabled","type":"checkbox","value":false,"advanced":true,"name":"disabled"}},"fresh":false}],"height":861}',
            'html' => '&lt;form id=&quot;form-app&quot;&gt;
&lt;fieldset&gt;

&lt;!-- Heading --&gt;
&lt;h3 class=&quot;legend&quot;&gt;Caroline &amp;amp; Steven&#039;s Wedding RSVP&lt;/h3&gt;

&lt;!-- Paragraph Text --&gt;
&lt;p&gt;We look forward to celebrating with you! Please reply by October 18, 2016&lt;/p&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_0&quot;&gt;First Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_0&quot; name=&quot;text_0&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_1&quot;&gt;Last Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_1&quot; name=&quot;text_1&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group required-control col-sm-6 no-padding-left&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_0&quot;&gt;Are you coming?&lt;/label&gt;
    &lt;select id=&quot;selectlist_0&quot; name=&quot;selectlist_0[]&quot; class=&quot;form-control&quot; required=&quot;&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;Can&#039;t Wait!&quot;&gt;Can&#039;t Wait!&lt;/option&gt;
        &lt;option value=&quot;Sorry To Miss Out&quot;&gt;Sorry To Miss Out&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Select List --&gt;
&lt;div class=&quot;form-group col-sm-6 no-padding&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;selectlist_1&quot;&gt;Persons will atend&lt;/label&gt;
    &lt;select id=&quot;selectlist_1&quot; name=&quot;selectlist_1[]&quot; class=&quot;form-control&quot;&gt;
        &lt;option value=&quot;&quot; disabled=&quot;&quot; selected=&quot;&quot;&gt;-Select-&lt;/option&gt;
        &lt;option value=&quot;1&quot;&gt;1&lt;/option&gt;
        &lt;option value=&quot;2&quot;&gt;2&lt;/option&gt;
    &lt;/select&gt;
&lt;/div&gt;

&lt;!-- Text --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;text_2&quot;&gt;Guest Name&lt;/label&gt;
    &lt;input type=&quot;text&quot; id=&quot;text_2&quot; name=&quot;text_2&quot; class=&quot;form-control&quot;&gt;
&lt;/div&gt;

&lt;!-- Text Area --&gt;
&lt;div class=&quot;form-group&quot;&gt;
    &lt;label class=&quot;control-label&quot; for=&quot;textarea_0&quot;&gt;Any special dietary requirements&lt;/label&gt;
    &lt;textarea id=&quot;textarea_0&quot; name=&quot;textarea_0&quot; rows=&quot;3&quot; class=&quot;form-control&quot;&gt;&lt;/textarea&gt;
&lt;/div&gt;

&lt;!-- Snippet --&gt;
&lt;div class=&quot;snippet&quot;&gt;&lt;p&gt;&lt;strong&gt;How to get there!&lt;/strong&gt;&lt;/p&gt;
&lt;p&gt;&lt;small&gt;The Francis Marion Hotel 387 King Street Charleston. South Carolina.&lt;/small&gt;&lt;/p&gt;
&lt;div id=&quot;map&quot;&gt;&lt;/div&gt;
&lt;style type=&quot;text/css&quot;&gt;
#map {
    width: 100%;
    height: 300px;
    margin-bottom: 15px;
    background-color: #f3f5f7;
}
&lt;/style&gt;&lt;/div&gt;

&lt;!-- Button --&gt;
&lt;div class=&quot;form-action&quot;&gt;
    &lt;input type=&quot;submit&quot; id=&quot;button_0&quot; name=&quot;button_0&quot; class=&quot;btn btn-primary&quot; value=&quot;Submit&quot;&gt;
&lt;/div&gt;

&lt;/fieldset&gt;
&lt;/form&gt;',
            'promoted' => 0,
            'slug' => 'wedding-rsvp-form',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => $this->fifteenMinutesAgo,
            'updated_at' => $this->fifteenMinutesAgo,
        ]);

    }

    public function safeDown()
    {

        // Builds and executes a SQL statement for dropping a DB table.
        $this->dropTable('{{%template}}');
        $this->dropTable('{{%template_category}}');

    }

}
