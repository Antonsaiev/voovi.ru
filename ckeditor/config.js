/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
 config.language = 'ru'; //Язык по умолчанию
    // config.skin = 'v2'; //Скин редактора (смотри в папке skins)
    // config.uiColor = '#AADC6E';
    // config.width = '100%'; //Ширина редактора
     config.startupFocus = true; //При открытии стр. где есть радактор - брать фокус на себя
     config.smiley_columns = 10; //Столбики со смайлами
     config.scayt_uiTabs = '1,0,1';
     config.toolbarStartupExpanded = false; //Прятать панель инстр. (по дефолту true)
     config.resize_enabled = false;
    // config.resize_minWidth = 900;
    // config.resize_minHeight = 900;
    // config.resize_dir = 'vertical'; //Изменять размер редактора только по высоте
     config.height = '150px'; //Высота редактора
};
