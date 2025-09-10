<!-- HTML Document -->

<div class="bbn-w-100 bbn-padding">
  <div class="bbn-padding bbn-radius bbn-border bbn-c bbn-content">
    <div class="bbn-w-100 bbn-padding">
      <bbn-code bbn-model="code"
                class="bbn-w-100"
                mode="sql"
                :wrap="false"
                ref="code"
                :autosize="true"
                :scrollable="false"
                :fill="false"/>
    </div>
    <div class="bbn-w-100 bbn-vpadding">
      <bbn-button @click="exec" :disabled="isRunning" icon="nf nf-cod-run_all">
        Run query
      </bbn-button>
    </div>
  </div>
  <div class="bbn-radius bbn-border bbn-padding bbn-w-100 bbn-vmargin"
       style="overflow: auto;"
       bbn-for="(item, i) in codes">
    <bbn-scroll axis="x"
                class="bbn-w-100">
      <pre class="bbn-block" bbn-html="item.code"/>
    </bbn-scroll>
    <div class="bbn-top-left">
      <div class="bbn-button-group">
        <bbn-button icon="nf nf-cod-copy"
                    @click="bbn.fn.copy(item.request.trim())"
                    :notext="true"
                    class="bbn-bg-purple bbn-white"
                    :disabled="false"/>
        <bbn-button icon="nf nf-cod-save"
                    @click="save(item)"
                    :notext="true"
                    class="bbn-bg-green bbn-white"
                    :disabled="false"/>
        <bbn-button icon="nf nf-cod-trash"
                    @click="codes.splice(i, 1)"
                    :notext="true"
                    class="bbn-bg-red bbn-white"
                    :disabled="false"/>
      </div>
    </div>
  </div>
</div>


