CKEDITOR.editorConfig = function( config ) {
    config.htmlEncodeOutput = false;
    config.entities = false;
    config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
    
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
    
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
    ];
    config.removeButtons = 'Outdent,Indent,CreateDiv,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,FontSize,ShowBlocks,About,Subscript,Superscript,Find,Replace,Cut,Copy,Paste,PasteText,PasteFromWord,Templates,Radio,Checkbox,Form,TextField,SelectAll,Select,Textarea,Button,ImageButton,HiddenField,Scayt,RemoveFormat,BidiLtr,BidiRtl,Language,Anchor,Source,Save,Templates,NewPage,Preview,Print,Undo,Redo';
};