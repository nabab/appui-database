<div class="bbn-w-100">
  <bbn-form v-if="state === 'editTable'"
            @success="success">
    <label><?=_("Table name")?></label>
    <bbn-input v-model="source.name"/>

    <div class="bbn-grid-full" v-if="source.name">
      <a href="javascript:;"
         @click="state = 'editOption'">
        <?=_("Configure the option")?>"
      </a>
    </div>
  </bbn-form>
  <bbn-form v-else-if="state === 'editOption'"
            :source="option">

    <div>
      <a href="javascript:;"
         @click="state = 'editTable'">
        <?=_("Back to")?>
      </a>
    </div>
    <div>
      &nbsp;
    </div>

    <label><?=_("Table title")?></label>
    <bbn-input v-model="option.text"/>

    <label><?=_("Table editor")?></label>
    <bbn-input v-model="option.editor"/>

  </bbn-form>
  <div v-else>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          v-if="option && (option.text !== option.code)"
          v-text="option.text"/>
    <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
          v-text="source.name"/>

  </div>
</div>