<!-- HTML Document -->
<div class="bbn-overlay bbn-flex-height">
  <div bbn-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       bbn-else-if="currentData?.error">
    <div class="bbn-block bbn-padding bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         bbn-text="currentData.error"/>
  </div>
  <div class="bbn-flex-fill" bbn-else>
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
                     label="<?= _('Table') ?>"
                     component="appui-database-table-columns-cell"/>
        <bbns-column label="<?= _("Action") ?>"
                     :width="200"
                     cls="bbn-c"
                     :component="$options.components.dropdown"/>
      </bbn-table>
    </div>
  </div>
</div>
