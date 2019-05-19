BX.namespace('BX.Sale.ArhicodeSaleOrder');

BX.Sale.ArhicodeSaleOrder = {
    /**
     * Встановлюємо початкові параметри
     */
    init: function(parameters)
    {
        var i;

        this.btnMinus = null;
        this.btnPlus = null;
        this.btnDelete = null;

        this.productId = null;
        this.basketCode = null;
        this.quantity = null;
        this.basePrice = null;
        this.totalPrice = null;
        this.discount = null;
        this.action = null;
        this.activeProductBlock = null;

        this.siteId = parameters.siteID;
        this.signedParamsString = parameters.signedParamsString;
        this.result = parameters.result;
        this.arrId = parameters.arrId;
        this.ajaxUrl = parameters.ajaxUrl;
        this.actionVariable = parameters.actionVariable;

        this.orderForm = BX(this.arrId.formId);

        this.orderCheck = false;
        this.order = {
            userInfo: {
                email:'',
                phone:'',
                name:'',
            },
            paySystem: {
                id:'',
                name:'',
            },
            delivery: {
                address:'',
            },
        };

        this.buttonStepText = ['Оформить заказ', 'Доставка и оплата', 'Проверить данные', 'Все верно, заказываю'];

        this.swiftOrder = BX.findChildren(this.orderForm, {"class":"ahc-swift-order"}, true)[0];
        this.stepBack = BX.findChildren(this.orderForm, {"class":"ahc-back"}, true)[0];
        this.allowOrder = BX(this.arrId.allowOrder);


        this.obGroupPay = BX.findChildren(this.orderForm, {"property":{'name':'group-pay'}, 'attribute':{'type':"radio"}}, true);
        this.obUserName = BX(this.arrId.userName);
        this.obUserPhone = BX(this.arrId.userPhone);
        this.obUserEmail = BX(this.arrId.userEmail);


        this.obDdelivery = BX.findChildren(this.orderForm, {"class":'ahc-delivery'}, true)[0];
        this.obDdeliveryName = BX.findChildren(this.obDdelivery, {'attribute':{'data-id':"name"}}, true)[0];
        this.obDdeliveryEmail = BX.findChildren(this.obDdelivery, {'attribute':{'data-id':"email"}}, true)[0];
        this.obDdeliveryPhone = BX.findChildren(this.obDdelivery, {'attribute':{'data-id':"phone"}}, true)[0];
        this.obDdeliveryAdres = BX.findChildren(this.obDdelivery, {'tag':'textarea'}, true)[0];


        this.obConfirmOrder = BX.findChildren(this.orderForm, {"class":'ahc-confirm-order'}, true)[0];
        this.obConfirmOrderName = BX.findChildren(this.obConfirmOrder, {'attribute':{'data-id':"name"}}, true)[0];
        this.obConfirmOrderPhone = BX.findChildren(this.obConfirmOrder, {'attribute':{'data-id':"phone"}}, true)[0];
        this.obConfirmOrderEmail = BX.findChildren(this.obConfirmOrder, {'attribute':{'data-id':"email"}}, true)[0];
        this.obConfirmOrderAddress = BX.findChildren(this.obConfirmOrder, {'attribute':{'data-id':"address"}}, true)[0];
        this.obConfirmOrderPay = BX.findChildren(this.obConfirmOrder, {'attribute':{'data-id':"delivery"}}, true)[0];



        this.bindBtnAction();
        this.setPhoneMask();

        console.log(this);
    },

    calculateDiscount: function()
    {
        var i,
            discountSum = 0,
            discount = this.result.DISCOUNT_APPLY_ORDER.BASKET;

        if(typeof discount === 'object')
        {
            for (i in discount)
            {
                if (this.result.QUANTITY_LIST[i])
                    discountSum += discount[i].DISCOUNT * this.result.QUANTITY_LIST[i];
            }
        }
        discountSum = Math.round(discountSum * 100) / 100;
        return discountSum;
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

        this.basePrice = BX(this.arrId.basePrice);
        this.obTotalPrice = BX(this.arrId.totalPrice);
        this.discount = BX(this.arrId.discount);

        this.buttonStep = BX(this.arrId.buttonStep);
        BX.bind(this.buttonStep, 'click', BX.proxy(this.buttonStepAction, this));

        this.panelList = [];
        for(i=1; i<5; i++)
            this.panelList[i] = BX.findChildren(this.orderForm, {"class":"ahc-panel-"+i}, true)[0];


        BX.bind(this.stepBack, 'click', BX.proxy(this.clickBackAction, this))
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

        this.activeProductBlock = BX.findParent(e.target, {"class":"ahc-product"});
        this.productId = productBlock.getAttribute('data-product-id');
        if(this.productId)
        {
            product = this.result.PRODUCT_LIST[this.productId];
            this.basketCode = product.BASKET_CODE;
            this.action = 'deleteProduct';
            this.sendRequest(this.action);
        }
        else this.errorMessage('Error: clickDeleteAction-this.productId');

        console.log(this.activeProductBlock);
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
        data['currencyCode'] = this.result.ORDER.CURRENCY;
        switch (this.action)
        {
            case 'deleteProduct':
                data['basketCode'] = this.basketCode;
                break;
            case 'changeQuantity':
                data['basketCode'] = this.basketCode;
                data['quantity'] = this.quantity;
                break;
            case "makeCurrentOrder":
                data['userName'] = this.order.userInfo.name;
                data['userPhone'] = this.order.userInfo.phone;
                data['userEmail'] = this.order.userInfo.email;
                data['userPaySystemId'] = this.order.paySystem.id;
                data['userAddress'] = this.order.delivery.address;
                data['totalPrice'] = this.totalPrice;
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
                console.log(': result :');
                console.log(result);

                switch (result.ACTION)
                {
                    case 'deleteProduct':
                        if(result.ERROR == 'N' || result.ERROR == 'basket-item-empty')
                        {
                            console.log(this.activeProductBlock);
                            BX.remove(this.activeProductBlock);
                            this.activeProductBlock = null;
                        }
                        break;
                }

                if(result.DATA)
                    this.setTotalPrise(result.DATA);


            }, this),
            onfailure: BX.delegate(function(message){
                console.log(message);
            }, this)
        });
    },

    setPhoneMask: function()
    {
        var result = new BX.MaskedInput({
            mask: '(999) 999 99 99',
            input: BX(this.arrId.userPhone),
            placeholder: '_'
        });
        var phone = false;

        if(this.result.USER_INFO.PERSONAL_PHONE != '')
            phone = this.result.USER_INFO.PERSONAL_PHONE;
        else if (this.result.USER_INFO.PERSONAL_MOBILE != '')
            phone = this.result.USER_INFO.PERSONAL_MOBILE;

        if (phone) result.setValue(phone);
            else result.setValue('0__ ___ __ __');
    },

    setTotalPrise: function(dataPrice)
    {
        this.basePrice.innerHTML = dataPrice.FORMATED_BASE_PRICE;
        this.obTotalPrice.innerHTML = dataPrice.PRICE_DISCOUNT.FULL_DISCOUNT_PRICE_FORMAT;
        this.discount.innerHTML = BX.Currency.currencyFormat((dataPrice.BASE_PRICE - dataPrice.PRICE_DISCOUNT.FULL_DISCOUNT_PRICE),this.result.ORDER.CURRENCY,true);

        this.totalPrice = dataPrice.PRICE_DISCOUNT.FULL_DISCOUNT_PRICE_FORMAT;
    },

    buttonStepAction: function(e)
    {
        var step = parseInt(this.buttonStep.getAttribute('data-step')),
            prevStep = step,
            nextStep = false,
            allowDiv = BX.findParent(this.allowOrder, {"class":"ahc-allow-order"}),
            error = false,
            i;

        console.log(this);
        console.log(step);

        switch (step)
        {
            case 1:
                step = 2;
                this.swiftOrder.style.display = 'none';
                allowDiv.style.display = 'block';
                this.stepBack.style.display = 'block';
                nextStep = true;
                break;
            case 2:
                this.order.userInfo.name = this.obUserName.value;
                if(this.order.userInfo.name.length < 3)
                {

                    error = true;
                }

                this.order.userInfo.email = this.obUserEmail.value;
                if(this.order.userInfo.email.length < 3)
                {

                    error = true;
                }

                this.order.userInfo.phone = this.obUserPhone.value;
                if(this.order.userInfo.phone.length < 3)
                {

                    error = true;
                }

                if(this.allowOrder.checked && !error)
                {
                    this.obDdeliveryName.innerHTML = this.order.userInfo.name;
                    this.obDdeliveryEmail.innerHTML = this.order.userInfo.email;
                    this.obDdeliveryPhone.innerHTML = this.order.userInfo.phone;
                    step = 3;
                    allowDiv.style.display = 'none';
                    nextStep = true;
                }
                break;
            case 3:
                for(i=0; i<this.obGroupPay.length; i++)
                {
                    this.order.delivery.address = this.obDdeliveryAdres.value;

                    if(this.obGroupPay[i].checked )
                    {

                        this.order.paySystem.id = this.obGroupPay[i].value;
                        this.order.paySystem.name = this.obGroupPay[i].getAttribute('data-name');
                        nextStep = true;
                        step = 4;

                        this.obConfirmOrderName.innerHTML = this.order.userInfo.name;
                        this.obConfirmOrderPhone.innerHTML = this.order.userInfo.phone;
                        this.obConfirmOrderEmail.innerHTML = this.order.userInfo.email;
                        this.obConfirmOrderAddress.innerHTML = this.order.delivery.address;
                        this.obConfirmOrderPay.innerHTML = this.order.paySystem.name;
                    }
                }
                break;
            case 4:

                this.orderCheck = true;
                this.action = 'makeCurrentOrder';
                this.sendRequest(this.action);
                break;
        }

        if(nextStep)
        {
            this.panelList[prevStep].style.display = 'none';
            this.panelList[step].style.display = 'block';
            this.buttonStep.setAttribute('data-step', step);
            this.buttonStep.innerHTML = this.buttonStepText[step-1];
        }
        console.log(this.order);
    },

    clickBackAction: function(e)
    {
        var step = parseInt(this.buttonStep.getAttribute('data-step')),
            prevStep = step,
            allowDiv = BX.findParent(this.allowOrder, {"class":"ahc-allow-order"}),
            i;

        console.log(this);
        console.log(step);

        switch (step)
        {
            case 1:
                step = 1;
                break;
            case 2:
                step = 1;
                this.swiftOrder.style.display = 'block';
                allowDiv.style.display = 'none';
                this.stepBack.style.display = 'none';
                break;
            case 3:
                step = 2;
                allowDiv.style.display = 'block';
                break;
            case 4:
                step = 3;
                break;
        }

        this.panelList[prevStep].style.display = 'none';
        this.panelList[step].style.display = 'block';
        this.buttonStep.setAttribute('data-step', step);
        this.buttonStep.innerHTML = this.buttonStepText[step-1];

        console.log(this.order);
    },

    errorMessage: function (message)
    {
        console.log(message);
    }
};
