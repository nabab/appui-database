<!-- HTML Document -->

<div class="appui-database-data-browser">
  <bbn-input :source="source"></bbn-input>
  <bbn-button icon="nf nf-custom-folder_open"
              :text="_('Browse')"
              @click.stop="browse">
  </bbn-button>
</div>