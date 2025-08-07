<bbn-form class="appui-database-table-history-integrate"
          :action="root + 'actions/table/history'"
          :source="formSource"
          :prefilled="true"
          confirm-message="<?=_("The creation of the initial history could take a long time, are you sure you want to continue?")?>"
          @success="onSuccess"
          @failure="onFailure">
  <div class="bbn-padding">
    <div class="bbn-bottom-space bbn-c"><?=_("The table is not empty, please select a user and a date to be used as a reference for the creation of the initial history of records")?></div>
    <div class="bbn-grid-fields">
      <span class="bbn-label"><?=_("User")?></span>
      <bbn-dropdown :source="users"
                    bbn-model="formSource.user"/>
      <span class="bbn-label"><?=_("Date")?></span>
      <bbn-datetimepicker bbn-model="formSource.date"
                          :show-second="true"/>
      <template bbn-if="columns.length">
        <span class="bbn-label"><?=_("Active column")?></span>
        <bbn-dropdown :source="columns"
                      bbn-model="formSource.activeColumn"
                      :nullable="true"/>
      </template>
    </div>
  </div>
</bbn-form>