BX.namespace('BX.Sale.ArhicodeSaleOrder');

BX.Sale.ArhicodeSaleOrder = {
    /**
     * Встановлюємо початкові параметри
     */
    init: function(parameters)
    {
        this.btnMinus = null;
        this.btnPlus = null;
        this.btnDelete = null;

        this.productId = null;
        this.basketCode = null;
        this.quantity = null;
        this.action = null;

        this.siteId = parameters.siteID;
        this.signedParamsString = parameters.signedParamsString;
        this.result = parameters.result;
        this.arrId = parameters.arrId;
        this.ajaxUrl = parameters.ajaxUrl;
        this.actionVariable = parameters.actionVariable;

        this.orderForm = BX(this.arrId.formId);
        this.bindBtnAction();

        console.log(this);
    },

    /**
     * Прикріпляємо події до кнопок
     */
    bindBtnAction: function()
    {
        var i;

        this.btnMinus = BX.findChildren(this.orderForm, {"class":"ahc-minus"}, true);
        for(i=0; i < this.btnMinus.length; i++)
            BX.bind(this.btnMinus[i], 'click', BX.proxy(this.changeQuantityAction, this));

        this.btnPlus = BX.findChildren(this.orderForm, {"class":"ahc-plus"}, true);
        for(i=0; i < this.btnPlus.length; i++)
            BX.bind(this.btnPlus[i], 'click', BX.proxy(this.changeQuantityAction, this));

        this.inputQuantity = BX.findChildren(this.orderForm, {"class":"ahc-quantity"}, true);
        for(i=0; i < this.inputQuantity.length; i++)
            BX.bind(this.inputQuantity[i], 'change', BX.proxy(this.changeQuantityAction, this));

        this.btnDelete = BX.findChildren(this.orderForm, {"class":"ahc-delete"}, true);
        for(i=0; i < this.btnDelete.length; i++)
            BX.bind(this.btnDelete[i], 'click', BX.proxy(this.clickDeleteAction, this));
    },

    changeQuantityAction: function(e)
    {
        var element = e.target,
            productBlock = BX.findParent(element, {"class":"ahc-product-panel"}),
            input = BX.findChildren(productBlock, {"class":'ahc-quantity'}, true)[0],
            product,
            value;

        this.quantity = false;
        value = input.value;
        if(BX.hasClass(element,'ahc-quantity'))
        {
            if(value < 1) input.value = 1;
                else input.value = value;
        }
        else if (BX.hasClass(element, 'ahc-plus')) {
            this.quantity = value;
            value++;
            input.value = value;
        } else {
            this.quantity = value;
            value--;
            if (value < 1) input.value = value = 1;
            else input.value = value;
        }

        this.productId = productBlock.getAttribute('data-product-id');
        if(this.productId && this.quantity != value)
        {
            product = this.result.PRODUCT_LIST[this.productId];
            this.basketCode = product.BASKET_CODE;
            this.quantity = value;
            this.action = 'changeQuantity';

            this.sendRequest(this.action);
        }
    },


    /**
     * Видаляємо товар по його коду ID в кошику - 'BASKET_CODE'
     */
    clickDeleteAction: function (e)
    {
        var productBlock = BX.findParent(e.target, {"class":"ahc-product-panel"}),
            product;
        this.productId = productBlock.getAttribute('data-product-id');
        if(this.productId)
        {
            product = this.result.PRODUCT_LIST[this.productId];
            this.basketCode = product.BASKET_CODE;
            this.action = 'deleteProduct';
            this.sendRequest(this.action);
        }
        else this.errorMessage('Error: clickDeleteAction-this.productId');
    },

    getData: function()
    {
        var data = {
            via_ajax: 'Y',
            sessid: BX.bitrix_sessid(),
            SITE_ID: this.siteId,
            signedParamsString: this.signedParamsString
        };

        data[this.actionVariable] = this.action;
        switch (this.action)
        {
            case 'deleteProduct':
                data['basketCode'] = this.basketCode;
                break;
            case 'changeQuantity':
                data['basketCode'] = this.basketCode;
                data['quantity'] = this.quantity;
                break;
        }

        console.log(data);
        return data;
    },

    sendRequest: function(action)
    {
        BX.ajax({
            method: 'POST',
            dataType: 'json',
            url: this.ajaxUrl,
            data: this.getData(),
            onsuccess: BX.delegate(function(result) {
                switch (action)
                {
                    case 'deleteProduct':
                        //this.refreshOrder(result);
                        break;
                }

                console.log(': result :');
                console.log(result);
            }, this),
            onfailure: BX.delegate(function(message){
                console.log(message);
            }, this)
        });
    },


    errorMessage: function (message)
    {
        console.log(message);
    }
};
