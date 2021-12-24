### TO DO
##### Цели [модуль]
- [ ] Проверка установленной ноды и ее версии из админки
- [ ] Запуск билда из админки и парсинг данных (все ли блоки найдены)
- [x] Брать блоки из других мест, например из /local/vueeditor/ для отделения модуля и блоков чисто для проекта
- [x] Свойство для инфоблоков
- [ ] Свойство для пользовательских полей
- [ ] Блок текста с редактором - выбрать редактор

### Блоки
на 22.12.2021
- vtext - Блок текста с виз.редактором quilljs
- vheader - Заголовок с выбором размера
- vquote - Текстовое поле для цитаты
- documents - Загрузка документов с названием и описанием
- gallery - Галерея изображений с названиями и описаниями
- youtubeVideo - Видео с youtube (по ссылке)
- vtable - Таблица
- vimage - Загрузка изображения

### Регистрация своих блоков
каждый блок должен иметь файл конфигурации, наименование по принципу
blockName.config.json<br>
пример конфига
```json
{
  "name": "Строка",
  "handler" : "\Gtd\Editor\Handler\String"
  "conf" : {
    "foo": "bar",
    "other": "some",
    "array": [
      "one", "two", "three"
    ]
  }
}
```


name - используется для текстового представления названия блока<br>
handler - класс для обработки данных перед выводом в публичную часть<br>
conf - пробрасывается в ваш блок

#### Ссылки

https://github.com/webpack/webpack/issues/6680


## Применение

Это модуль для 1С-Битрикс. В нём содержится
- vue-компонент редактора на typescript со сборкой через webpack (папка app)
- код для подключения компонента как типа свойств инфоблока и uf-поля
- бэк для некоторых блоков, например сохранение файлов (папка service)


### Установка
```shell
composer require nnagornyy/gtd.vueeditor
```
После этого **установить модуль в админке битрикса** через Установленные решения.<br>
После установки должна появиться папка `/local/vueeditor`<br>
Подключите модуль в init.php `\Bitrix\Main\Loader::includeModule('gtd.vueeditor');` (он подключит js и стили редактора).


### Добавление блока
При сборке блоки из `/local/vueeditor` копируются в `модуль/app/src/ext_block`

При разработке блоков можно делать правки в самом ext_block, но потом обязательно скопировать в /local/vueeditor и закоммитить.

Структура блока: <br>
папка = код названия блока, <br>
конфиг = код.config.json, <br>
компонент = код.vue

В компоненте в data обязательно должно быть поле `editorData`, информация из него будет сохраняться как значение блока.

В качестве примера можно скопировать vheader со своим названием и делать на основе него. <br>
Префикс v в некоторых готовых блоках нужен только для того, чтобы они не пересекались с существующими тегами.<br>

Пример компонента:
```vue
<template>
  <el-input v-model="editorData" placeholder="Введите заголовок"></el-input>
</template>

<script>
    export default {
      name: "simpleHeader",
      data(){
        return{
          editorData: '',
        }
      }
    }
</script>

<style scoped>

</style>
```

Если инициируем редактор вручную (например при использовании как вью-компонент), добавляем название нового блока в параметр allowBlock конструктора.


### Сборка

Изнутри модуля
```
cd local/modules/gtd.vueeditor/app
yarn build-prod
```

Или можно добавить команду сборки снаружи (пример для `/local/package.json`)
```json
"scripts": {
    "build:editor": "cd modules/gtd.vueeditor/app; yarn; yarn build-prod",
    "dev:editor": "cd modules/gtd.vueeditor/app; yarn; yarn build-dev"
  },
```

Артефакты сборки сохраняются в `/local/vueeditor_assets/`<br>
Если вы коммитите артефакты - нужно руками добавлять папку в гит после пересборки с новыми блоками. <br>
Если вы собираете налету - добавьте папку в гитигнор.

### Использование как vue-компонента

Конструктор блочного редактора существует как глобальная переменная `document.vueeditor(value, inputName, allowBlock, appId)`.<br>

Можно добавить компонент-обертку в свой проект, которая будет инициировать объект редактора. <br>
Пример:
```vue
<template>
  <div id="editor"></div>
</template>

<script>

export default {
  name: "editor",
  props:{
    value:{
      type: Array,
      default: function(){
        return [];
      }
    },
    allowBlocks:{
      type: Array,
      default: function(){
        return [];
      }
    }
  },
  data(){
    return {}
  },
  mounted() {
    const editor = new document.vueeditor(JSON.parse(JSON.stringify(this.value)) , 'someName', this.allowBlocks, 'editor');
    editor
        .onValueChange((value) => {
          this.$emit('input', JSON.parse(JSON.stringify(value)));
        })
        .initEditor();
  }
}
</script>
```

А в компоненте, где используется блочный редактор, добавляем
```vue
<editor
  v-model="event.contentRaw"
  :allow-blocks="editorBlocks"
></editor>
...
import Editor from 'path2component/editor.vue';
...
components: { Editor },
...
editorBlocks: [ 'vtext', 'documents', 'vquote', 'someNewBlock' ],
```


### Использование как свойства ИБ
// todo

### Использование как UF-поля
// todo

### Добавление обработчика на получение данных
При получении данных из БД можно воспользоваться хендлерами блоков и обработать данные прежде, чем вывести их.

**Важно!** В сам блочный редактор должны идти необработанными данные, обработка нужна для вывода в публичке.

0. Используем парсер, чтобы получить обработанные данные блока
```php
public function getContent(): array
{
    \Bitrix\Main\Loader::includeModule('gtd.vueeditor');
    $arr = $this->getContentArrayFromDB();
    $editor = new \Gtd\VueEditor\Block\BlockParser($arr, $_SERVER['DOCUMENT_ROOT'].'/local/vueeditor/');
    $res = $editor->processingBlock();
    return $res;
}
```
Первый параметр - десериализованный массив из БД как он пришёл из блочного редактора.<br>
Второй параметр (необязательный) - где искать конфиги для кастомных блоков (по умолчанию ищет в модуль/app/src/ext_block).<br>
<br>
Путь к конфигам нужен, если вы коммитите артефакты и не запускаете сборку на бою. Тогда компоненты из /local/vueeditor **не** попадут в модуль/app/src/ext_block на бою, и скрипт не найдет конфиги в папке по умолчанию.

1. Добавляем класс, имплементирующий интерфейс `\Gtd\VueEditor\Block\Handler`


2. Добавляем путь к классу в конфиг блока.<br>
   например, в myblock.conj.json: `"handler": "\\My\\Module\\Blocks\\MyBlock"`


3. Реализуем методы setBlock(\Gtd\VueEditor\Block\Block $block) и getData()
   Можно скопировать их из дефолтного хендлера `\Gtd\VueEditor\Block\DefaultHandler` в `gtd.vueeditor/lib/block/defaulthandler.php`

Метод setBlock получает блок как есть, здесь можно обработать данные или просто записать их в переменную класса, чтобы потом использовать в getData.<br>
Метод getData должен вернуть данные, которые попадут в поле `content` блока и будет потом использовать при выводе.



### Пресеты
// todo