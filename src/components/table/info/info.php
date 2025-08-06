<!-- HTML Document -->

<div class="bbn-overlay">
  <bbn-dashboard :scrollable="true">
    <bbns-widget :closable="false"
                 label="<?= _("Properties") ?>"
                 component="appui-database-table-info-widget-config"
                 :source="source"/>

    <bbns-widget :closable="false"
                 label="<?= _("Table's options") ?>"
                 component="appui-database-table-info-widget-option"
                 :source="source"/>

    <bbns-widget :closable="false"
                 label="<?= _("Relations") ?>"
                 component="appui-database-table-info-widget-relations"
                 :source="source"/>
  </bbn-dashboard>
</div>