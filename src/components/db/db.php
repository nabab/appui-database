<!-- HTML Document -->
<div class="bbn-overlay bbn-flex-height">
  <div v-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       v-else-if="currentData.error">
    <div class="bbn-block bbn-padded bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         v-text="currentData.error"/>
  </div>
  <div class="bbn-flex-fill" v-else>
    <div class="bbn-100">
      <bbn-table ref="table"
                 @toggle="checkMultipleSelected"
                 :source="root + 'data/tables/' + currentData.engine + '/' + currentData.host + '/' + currentData.db"
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