<?php


namespace SuttonSilver\CustomCheckout\Plugin\Magento\Checkout\Block\Checkout;
use SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionValues\CollectionFactory as QuestionValuesCollectionFactory;
use SuttonSilver\CustomCheckout\Api\QuestionRepositoryInterfaceFactory;

use Magento\Checkout\Model\Session as Session;
class LayoutProcessor
{
    /**
     * @var \Magento\Checkout\Block\Checkout\AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\Collection
     */
    protected $countryCollection;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\Collection
     */
    protected $regionCollection;

    protected $delivery;

    protected $questionsCollection;
    protected $questionsValuesCollection;
    protected $session;
    protected $questionFactory;


    public function __construct(
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
        \SuttonSilver\CustomCheckout\Model\Attribute\Source\Delivery $delivery,
        QuestionCollectionFactory $questionsCollection,
        QuestionRepositoryInterfaceFactory $questionFactory,
        QuestionValuesCollectionFactory $questionsValuesCollection,
        Session $session
    ) {
        $this->merger = $merger;
        $this->countryCollection = $countryCollection;
        $this->regionCollection = $regionCollection;
        $this->delivery = $delivery;
        $this->questionsCollection = $questionsCollection;
        $this->questionsValuesCollection = $questionsValuesCollection;
        $this->session = $session;
        $this->questionFactory = $questionFactory;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {

        $elements = [
            'country_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('Country'),
                'options' => $this->countryCollection->loadByStore()->toOptionArray(),
                'value' => null
            ],
            'region_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('County'),
                'options' => $this->regionCollection->load()->toOptionArray(),
                'value' => null,
            ]
        ];

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address'])
        ) {

            $fieldSetPointer = &$jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'homeAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;

        }

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['firstname']['config']['template'] = 'ui/form/element/hidden';

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['lastname']['config']['template'] = "ui/form/element/hidden";


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']['config']['template'] = "ui/form/element/hidden";

        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['customer-email']);

        unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['additional-payment-validators']['children']['email-validator']);

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['label'] = "Postcode";

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['label'] = "";

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][0]['label'] = "First Line of Address";

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['label'] = "County";

	    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
	    ['shippingAddress']['children']['shipping-address-fieldset']['children']['region']['valueUpdate'] = "input";

	    $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
	    ['shippingAddress']['children']['shipping-address-fieldset']['children']['dx_number']['default'] = "DX";

       $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
	        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['config']['template'] = 'SuttonSilver_CustomCheckout/ui/group/group';

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['set_shipping'] = [
            'component' => 'SuttonSilver_CustomCheckout/js/form/element/checkbox-set-shipping',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/element/checkbox-set',
                'options' => $this->delivery->toOptionArray(),
                'id'=>'select_address',
                'multiple'=> false,
            ],
	        'default' =>'default_shipping',
	        'value' =>'default_shipping',
	        'initialValue' =>'default_shipping',
            'dataScope' => 'shippingAddress.set_shipping',
            'selected' => 'default_shipping',
            'label' => 'Deliver To',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => ['required-entry' => true],
            'sortOrder' => 2,
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['select-address-error'] = [
            'component' => 'Magento_Ui/js/form/components/html',
            'config' => [
                'customScope' => 'shippingAddress',
            ],
            'dataScope' => 'shippingAddress.select_address',
            'content' => '',
            'provider' => 'checkoutProvider',
            'sortOrder' => 3,
            'additionalClasses' => 'warning shiping-address-warning',
        ];

        $questions = $this->questionsCollection->create()
            ->addFieldToFilter('question_is_active',1)
            ->addFieldToFilter('product_skus', $this->getAllCartItems());

        $options = [];
        foreach($questions as $question)
        {
            $name = strtolower(trim(str_replace(' ','-',$question->getQuestionName())));
            $label = __($question->getQuestion());
            $placeholder = trim($question->getQuestionPlaceholder());
            $required = ($question->getQuestionIsRequired()) ? true : false;

            $values = $this->questionsValuesCollection->create()
                ->addFieldToFilter('question_id',$question->getId());

            $valueArray = [];
            foreach($values as $value)
            {
                $valueArray[] = [
                    'value' => $value->getData('question_saved_value') != '' ? $value->getData('question_saved_value') : $value->getData('question_value'),
                    'label' => __($value->getData('question_value')),
                ];
            }

            switch($question->getQuestionType())
            {
                case 'text':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input',
                            'id'=>$name,
                        ],
                        'additionalClasses' => '',
                    ];
                    break;
                case 'textarea':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/textarea',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/textarea',
                            'id'=>$name,
                        ],
                        'additionalClasses' => '',
                    ];
                    break;
                case 'select':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'template' => 'ui/form/field',
                            'customScope' => 'additionalDetails',
                            'elementTmpl' => 'ui/form/element/select',
                            'options' => $valueArray,
                            'id'=>$name
                        ],

                    ];
                    break;
                case 'checkbox':
                case 'radio':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/checkbox-set',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/element/checkbox-set',
                            'options' => $valueArray,
                            'id'=>$name,
                            'multiple'=> ($question->getQuestionType()=='radio') ? false : true,
                        ],

                    ];
                    break;

                case 'yes-no':
                        $options[$name] = [
                            'component' => 'Magento_Ui/js/form/element/single-checkbox',
                            'config' => [
                                'customScope' => 'additionalDetails',
                                'template' => 'ui/form/field',
                                'elementTmpl' => 'ui/form/components/single/switcher',
                                'id' => $name,
                            ],
                            'checked' => 0,
                            'additionalClasses' => '',
                            'prefer' => 'toggle',
                            'valueMap' => ['true' => 1, 'false' => 0],
                        ];

                    break;

            }

            $options[$name]['placeholder'] = $placeholder;
            $options[$name]['label'] = $label;
            $options[$name]['dataScope'] = 'additionalDetails.'.$name;
            $options[$name]['sortOrder'] = $question->getQuestionPosition() ?: 1;
            $options[$name]['visible'] = true;
            $options[$name]['provider'] = 'checkoutProvider';

	        $description = $question->getQuestionTooltip();
            if($description != '') {
                $options[$name]['config']['tooltip']['description'] = strip_tags($description);
            }

            if($required)
            {
                $options[$name]['validation'] = ['required-entry' => $required];
            }

            $dependsOn = $question->getQuestionDependsOn();
            if(count($dependsOn) > 0) {
                foreach ($dependsOn as $depndent)
                {
                    try {
                        $depndent = (int)$depndent;
                        $dependentQuestion = $this->questionFactory->create()->getById($depndent);
                        $dependentName = strtolower(trim(str_replace(' ', '-', $dependentQuestion->getQuestionName())));
                        $options[$name]['config']['imports']['visible'] = '${ $.parentName }.'."$dependentName:checked";
                        $options[$dependentName]['config']['exports']['value'] = '${ $.provider }:value';
                    }catch(\Exception $e)
                    {

                    }
                }
            }

        }

      if(isset($jsLayout['components']['checkout']['children']['steps']['children']['my-new-step'])) {
        	if(count($options) > 0) {
		        $jsLayout['components']['checkout']['children']['steps']['children']
		        ['my-new-step']['children']['custom-checkout-form-additional'] = [
			        'component'   => 'uiComponent',
			        'displayArea' => 'additional',
			        'children'    => $options
		        ];
	        }
        }


	    /* config: checkout/options/display_billing_address_on = payment_method */
	    if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
		    ['payment']['children']['payments-list']['children']
	    )) {

		    foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
		    ['payment']['children']['payments-list']['children'] as $key => $payment) {

			    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
			    ['payment']['children']['payments-list']['children'][$key]['component'] = "SuttonSilver_CustomCheckout/js/view/billing-address";


			    /* company */
			    if (isset($payment['children']['form-fields']['children']['company'])) {

				    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['company']);
			    }


			    if (isset($payment['children']['form-fields']['children']['dx_number'])) {
				    unset( $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
					    ['payment']['children']['payments-list']['children'][ $key ]['children']['form-fields']['children']
					    ['dx_number'] );
			    }


			    if (isset($payment['children']['form-fields']['children']['postcode'])) {

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['firstname']['config']['template'] = "ui/form/element/hidden";

				    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				         ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				         ['firstname']['validation']);

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['lastname']['config']['template'] = "ui/form/element/hidden";

				    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
					    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
					    ['lastname']['validation']);

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['telephone']['config']['template'] = "ui/form/element/hidden";

				    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
					    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
					    ['telephone']['validation']);
			    	
				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['postcode']['label'] = "Postcode";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['street']['label'] = "";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['street']['config']['template'] = 'SuttonSilver_CustomCheckout/ui/group/group';

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['street']['children'][0]['label'] = "First Line of Address";

				    //postcode
				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['postcode']['component'] = "SuttonSilver_CustomCheckout/js/form/element/post-code";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['country_id']['component'] = "SuttonSilver_CustomCheckout/js/form/element/select-country";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['region_id']['component'] = "SuttonSilver_CustomCheckout/js/form/element/region";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['postcode']['placeholder'] = "eg. MK42 7AB";

				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['region_id']['label'] = "County";


				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
				    ['postcode']['validation'] = ['required'=>true,'validate-zip-international'=>true];

				    //custom
				    $method = substr($key, 0, -5);
				    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
				    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']['address_choose'] = [
				    	'component' => 'SuttonSilver_CustomCheckout/js/form/element/select-address',
					    'config' => [
					        'template' => 'ui/form/field',
						    'elementTmpl' => 'ui/form/element/select',
					        'customScope' => 'billingAddress' . $method,
					        'customEntry' => null,
					        'tooltip' => null,
					    ],
					    'visible' => false,
					    'sortOrder' => 130,
					    'placeholder' => __('--Please Select Address--'),
					    'label' => 'Choose Address',
					    'provider' => 'checkoutProvider',
					    'dataScope' => 'billingAddress' . $method . '.address_choose',
					    'dataScopePrefix' => 'billingAddress' . $method,
				    ];

			    }

		    }
	    }

	    /* config: checkout/options/display_billing_address_on = payment_page */
	    if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
		    ['payment']['children']['afterMethods']['children']['billing-address-form']
	    )) {



		    /* company */
		    if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
			    ['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']
			    ['children']['company']
		    )) {
			    unset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
			    ['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']
			    ['children']['company']);
		    }

	    }



        return $jsLayout;
    }

    /**
     * Get  \Magento\Checkout\Model\Session $cart all visible items.
     * @return array $ids
     */
    public function getAllCartItems()
    {

        $items = $this->session->getQuote()->getAllVisibleItems();
        $ids = [];
        foreach ($items as $item)
        {
            $ids[] =  array('finset'=> array($item->getProduct()->getId()));
        }
        return $ids;
    }
}
