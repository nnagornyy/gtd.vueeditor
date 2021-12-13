<script>
    new document.gtdEditor([], '<?=$this->getInputName()?>', [], '<?=$this->getAppId()?>'
    )
        .setJsonValue(<?=$this->getValue()?>)
        .setJsonAllowBlocks(<?=$this->getAllowBlocks()?>)
        .initEditor();
</script>