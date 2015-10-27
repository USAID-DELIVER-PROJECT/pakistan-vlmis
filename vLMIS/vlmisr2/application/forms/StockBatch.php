<?php

class Form_StockBatch extends Zend_Form {

    private $_fields = array(
        "item_pack_size_id" => "Vaccine",
        "status" => "Status",
        "number" => "Batch Number",
        "transaction_refernece" => "Transaction Refernece"
    );
    private $_list = array(
        'item_pack_size_id' => array()
    );

    public function init() {
        //Generate Products(items) Combo
        $items = new Model_ItemPackSizes();
        $result2 = $items->getAllItems();
        $this->_list["item_pack_size_id"][''] = "Select";
        foreach ($result2 as $item) {
            $this->_list["item_pack_size_id"][$item['pkId']] = $item['itemName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "number":
                case "transaction_refernece":
                    $this->addElement("text", $col, array(
                        "attribs" => array("class" => "form-control"),
                        "allowEmpty" => false,
                        "filters" => array("StringTrim", "StripTags"),
                        "validators" => array()
                    ));
                    $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
                    break;
                case "status":
                    $this->addElement("radio", $col, array(
                        "attribs" => array(),
                        "allowEmpty" => true,
                        'separator' => '',
                        "filters" => array("StringTrim", "StripTags"),
                        "validators" => array(),
                        "multiOptions" => array(
                            "1" => "Running",
                            "2" => "Stacked",
                            "3" => "Finished",
                            "4" => "Total (Running + Stacked)"
                        ),
                        'options' => array(
                            'label' => 'Title',
                            'labelAttributes' => array(
                                'class' => 'radio-inline',
                            ),
                        ),
                    ));
                    //$this->getElement($col)->setAttrib("label_class", "radio-inline");
                    $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag")->removeDecorator("<br>");
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                $this->addElement("select", $col, array(
                    "attribs" => array("class" => "form-control"),
                    "filters" => array("StringTrim", "StripTags"),
                    "allowEmpty" => true,
                    "required" => false,
                    "registerInArrayValidator" => false,
                    "multiOptions" => $this->_list[$col],
                    "validators" => array(
                        array(
                            "validator" => "Float",
                            "breakChainOnFailure" => false,
                            "options" => array(
                                "messages" => array("notFloat" => $name . " must be a valid option")
                            )
                        )
                    )
                ));
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
            }
        }
    }

}
