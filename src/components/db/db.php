<!-- HTML Document -->
<div class="bbn-overlay bbn-flex-height">
  <div class="bbn-flex-fill">
    <div class="bbn-100">
      <bbn-table ref="table"
                 @toggle="checkMultipleSelected"
                 :source="root + 'data/tables/' + source.engine + '/' + source.host + '/' + source.db"
                 :selection="true"
                 :pageable="true"
                 :showable="true"
                 :limit="50"
                 :toolbar="toolbar"
                 :info="true"
                 uid="name">
        <bbns-column field="name"
                     title="<?=_('Table')?>"
                     component="appui-database-table-columns-cell"/>
        <bbns-column title="<?=_("Action")?>"
                     :width="200"
                     cls="bbn-c"
                     :component="$options.components.dropdown"/>
      </bbn-table>
    </div>
  </div>
</div>