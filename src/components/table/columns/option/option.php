<!-- HTML Document -->

<bbn-form :action="root + 'actions/table/option'"
          :source="source.option">
  <div class="bbn-grid-fields bbn-padded">
    <label>Title</label>
    <div>
      <bbn-input v-model="source.option.text"/>
    </div>
    <label>Component</label>
    <div>
      <bbn-input v-model="source.option.component"/>
    </div>
    <label>Editor</label>
    <div>
      <bbn-input v-model="source.option.editor"/>
    </div>
  </div>
</bbn-form>