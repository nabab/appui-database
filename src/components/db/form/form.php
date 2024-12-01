<!-- HTML Document -->
<bbn-form :source="data"
          :scrollable="true"
          :action="root + 'actions/database/create'">
  <div class="bbn-c bbn-overlay bbn-middle">
    <div class="bbn-block bbn-nowrap bbn-lg bbn-padding">
      <span class="bbn-right-space"><?= _("Database name") ?></span>
      <bbn-input v-model="data.name"/>
    </div>
  </div>
</bbn-form>
