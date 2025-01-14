<!-- HTML Document -->
<div class="bbn-w-100 bbn-no-wrap bbn-c bbn-padding">
  <bbn-input @keydown.enter.stop.prevent="lookupUID"
             :placeholder="_('Look up UID in History')"
             size="16"
             maxlength="16">
  </bbn-input>&nbsp;
  <bbn-button :label="_('Look up')"
              url="history/list"
              icon="nf nf-fa-search"
              :notext="true">
  </bbn-button><br><br>
  <bbn-button :label="_('History list')"
              icon="nf nf-mdi-format_list_bulleted"
              url="history/list">
  </bbn-button>
</div>