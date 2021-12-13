import Vue from 'vue';
import ElementUI from 'element-ui';
// @ts-ignore
import VueYoutube from 'vue-youtube'
import 'element-ui/lib/theme-chalk/index.css';
// @ts-ignore
import hello from './hello.vue';
import baseField from "./baseField";
// @ts-ignore
import locale from 'element-ui/lib/locale/lang/ru-RU'

Vue.use(ElementUI, { locale });
Vue.mixin(baseField);
Vue.use(VueYoutube);



export default class BlockEditor{

    private _inputName:String = "";
    private _value:Array<any> = [];
    private _allowBlock:Array<string> = [];
    private _appId: string = "";
    private _onValueChange:valueChangeCallback;
    private _onRendered:any;

    constructor(value: Array<any>, inputName: string = "", allowBlock: Array<any>, appId: string = "") {
        this.setValue(value);
        this.setAllowBlocks(allowBlock);
        this._appId = appId.length > 0 ? appId : this.makeId(10);
        this._inputName = inputName !== "" ? inputName : this.makeId(10);
    }

    initEditor(){
        document.addEventListener('DOMContentLoaded', () => {
            new Vue({
                data:() => {
                    return {
                        val: this._value,
                        inputName: this._inputName,
                        allowBlocks: this._allowBlock,
                        callback: this._onValueChange
                    }
                },
                render: (h) => h(hello),
                mounted: () => {
                    if(typeof this._onRendered === 'function'){
                        this._onRendered();
                    }
                }
            }).$mount('#' + this._appId)
        });
    }


    setValue(value:Array<any>): BlockEditor
    {
        this._value = value;
        return this;
    }

    setJsonValue(value:string): BlockEditor
    {
        this._value = JSON.parse(value) || [];
        return this;
    }

    setAllowBlocks(value:Array<string>): BlockEditor
    {
        this._allowBlock = value;
        return this;
    }

    setJsonAllowBlocks(value:string): BlockEditor{
        this._allowBlock = value.length > 0 ? JSON.parse(value) : [];
        return this;
    }

    setInputName(inputName:string): BlockEditor
    {
        this._inputName = inputName;
        return this;
    }

    onValueChange(callBack:valueChangeCallback): BlockEditor
    {
        if(typeof callBack === "function"){
            this._onValueChange = callBack;
        }
        return this;
    }

    onRendered(callBack:any): BlockEditor {
        if(typeof callBack === 'function'){
            this._onRendered = callBack;
        }
        return this;
    }

    makeId(length:number = 10) :string
    {
        let result           = '';
        const characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }
}

type valueChangeCallback = (value: any) => void
