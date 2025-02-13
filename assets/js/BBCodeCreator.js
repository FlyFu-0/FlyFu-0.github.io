window.onload = function () {

    const htmlCollection = document.getElementsByTagName('textarea');

    if (!htmlCollection) return;

    Array.from(htmlCollection).forEach((el) => sceditor.create(el, {
        width: '100%',
        height: '200px',
        resizeMinHeight: 200,
        resizeMaxHeight: 500,
        resizeWidth: false,
        autofocus: true,
        emoticonsEnabled: false,
        parserOptions: {
            removeEmptyTags: true,
            fixInvalidChildren: true,
        },
        format: 'bbcode',
        style: 'assets/js/lib/BBCodeEditor/minified/themes/content/default.min.css',
        toolbar: 'bold,italic,underline,strike,subscript,superscript|left,center,right,justify|font,size,color,removeformat|cut,copy,pastetext|bulletlist,orderedlist,indent,outdent|code,quote|horizontalrule,image,email,link,unlink|youtube,date,time|print,maximize,source'
    }));
}