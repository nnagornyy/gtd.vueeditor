<script>
    new document.vueeditor([], '<?=$this->getInputName()?>', [], '<?=$this->getAppId()?>'
    )
        .setJsonValue(<?=$this->getValue()?>)
        .setJsonAllowBlocks(<?= CUtil::PhpToJSObject($this->getAllowBlocks()) ?>)
        .initEditor();
</script>
