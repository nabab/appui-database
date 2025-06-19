<!-- HTML Document -->
<bbn-form :source="data"
          :scrollable="false"
          :action="root + 'actions/database/create'">
  <div class="bbn-grid-fields bbn-padding">
    <span class="bbn-right-space"><?= _("Name") ?></span>
    <bbn-input bbn-model="data.name"/>
  </div>
</bbn-form>
