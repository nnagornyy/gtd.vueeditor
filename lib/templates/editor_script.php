<script>
    document.gtdEditor(
        <?=$this->getValue()?>,
        '<?=$this->getInputName()?>',
        '<?=$this->getAppId()?>',
        '<?=$this->getAllowBlocks()?>',
        '<?=$this->getPropertyId()?>'
    );
</script>