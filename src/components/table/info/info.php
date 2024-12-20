<!-- HTML Document -->

<div class="bbn-overlay">
  <bbn-dashboard>
    <bbn-widget :closable="false"
                title="<?= _("Properties") ?>">
      <appui-database-table-info-widgets-config :source="source"/>
    </bbn-widget>

    <bbn-widget :closable="false"
                title="<?= _("Table's options") ?>">
      <appui-database-table-info-widgets-option :source="source"/>
    </bbn-widget>

    <bbn-widget :closable="false"
                title="<?= _("Relations") ?>">
      <appui-database-table-info-widgets-relations :source="source"/>
    </bbn-widget>
  </bbn-dashboard>
</div>