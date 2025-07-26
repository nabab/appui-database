(() => {
  return {
    data() {
      return {
        root: appui.plugins['appui-database'] + '/',
        formData: {
          id: this.source.option?.id,
          type: 'dcolumns',
          dcolumns: this.source.option?.dcolumns || [],
          engine: this.source.engine,
          host: this.source.host,
          db: this.source.db,
          table: this.source.table
        }
      }
    },
    computed: {
      fields() {
        const ar = [];
        bbn.fn.iterate(this.source.structure.fields, (f, name) => {
          ar.push(bbn.fn.extend({name}, f));
        });

        return bbn.fn.order(ar, 'position');
      },
      textValues() {
        return this.fields.map(f => {
          return {text: f.name, value: f.name};
        })
      }
    },
    methods: {
      onSuccess() {
        if (this.source.option.dcolumns !== this.formData.dcolumns) {
          this.source.option.dcolumns = this.formData.dcolumns;
        }
      }
    }
  };
})();
