<script>
    new document.vueeditor([], '<?=$this->getInputName()?>', [], '<?=$this->getAppId()?>'
    )
        .setJsonValue(<?=$this->getValue()?>)
        .setAllowBlocks(<?=$this->getAllowBlocks()?>)
        .initEditor();
</script>