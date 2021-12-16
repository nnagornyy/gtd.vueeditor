<script>
    new document.vueeditor([], '<?=$this->getInputName()?>', [], '<?=$this->getAppId()?>'
    )
        .setJsonValue(<?=$this->getValue()?>)
        .setJsonAllowBlocks(<?=$this->getAllowBlocks()?>)
        .initEditor();
</script>