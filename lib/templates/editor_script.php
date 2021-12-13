<script>
    new document.gtdEditor(
        <?=$this->getValue()?>,
        '<?=$this->getInputName()?>',
        '<?=$this->getAllowBlocks()?>',
        '<?=$this->getAppId()?>'
    ).initEditor();
</script>