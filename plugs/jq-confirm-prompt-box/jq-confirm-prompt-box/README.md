# jquery-prompt
jquery prompt plugin for electron and the browser.

demo: https://angeal185.github.io/jquery-prompt/

### Installation

npm

```sh
$ npm install jquery-prompt
```

#### electron
ensure jquery is installed.
```js
require('jquery-prompt');
```
```html
<link rel="stylesheet" href="./dist/css/jq-prompt.min.css">
```

#### browser

```html
<link rel="stylesheet" href="./dist/css/jq-prompt.min.css">
<script src="./dist/js/jq-prompt.min.js">
```

### info


```js
// prompt string defaults
{
  header: 'prompt', //header text
  type: 'text', //input type
  speed: 'fast', //animation speed
  placeholder: 'cannot be empty', //placeholder text
  cBtn: 'Cancel', //cancel button text
  sBtn: 'Submit', //submit button text
  error: 'cannot be empty' //error msg text
}

// prompt boolean defaults
{
    header: 'prompt', //header text
    speed: 'fast', //animation speed
    cBtn: 'Cancel', //cancel button text
    sBtn: 'Submit' //submit button text
}
//demo

// set global prompt string defaults
$.xPrompt.defaults = {
  placeholder: "test",
  error: "error"
};

// set global prompt boolean defaults
$.xPromptQ.defaults = {
  speed: 'slow'
};

//prompt string demo
//$.xPrompt(options,callback)

$('.ptest').on('click', function(){
  $.xPrompt({header: 'header', placeholder: 'enter text'}, function(i){
    // do something with string
    console.log(i)
  })
})

//prompt boolean demo
//$.xPromptQ(options,callback)

$('.ptestQ').on('click', function(){
  $.xPromptQ({header: 'are you sure'}, function(i){
    // do something with boolean
    console.log(i)
  })
})

```
