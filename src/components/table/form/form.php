<!-- HTML Document -->

<div class="bbn-padded" style="width: 50em; height: 20em" >
  <bbn-form :source="source"
            ref="form"
            :data="formData">
    <div class="bbn-grid-fields bbn-padded bbn-lg">
      <label><?=_("Table's name")?></label>
      <div>
        <bbn-input class=" bbn-padded"v-model="source.comment"/>
      </div>

      <label><?=_("Comment")?></label>
      <div>
        <bbn-input class=" bbn-padded" v-model="source.name"/>
      </div>
    </div>

    <div class="bbn-grid-fields bbn-padded">
      <template v-for="(col, i) in columns">
        <div>
          <span v-if="edited === i" />
          <span v-else v-text="col.name" class="bbn-b"/>
        </div>
        <div v-if="edited === i">
          <appui-database-column-form :source="col"
                                      :otypes="source.types"
                                      :engine="source.engine"
                                      :host="source.host"
                                      :db="source.db"
                                      @cancel="onCancel"
                                      @change="edited = -1"
                                      >
          </appui-database-column-form>
        </div>
        <div v-else v-text="getColDescription(col)" class="bbn-pre"> </div>
      </template>
    </div>



    <div class="bbn-vpadded">
      <bbn-button @click="addColumn()"
                  :disabled="edited !== -1"
                  >
        <?= _("Add a new column") ?>
      </bbn-button>
    </div>
  </bbn-form>
</div>