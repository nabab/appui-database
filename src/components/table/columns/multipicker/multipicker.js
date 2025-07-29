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
        const ar = [];
        const st = this.source.structure;
        bbn.fn.each(this.fields, f => {
          ar.push({text: f.name, value: f.name});
          if (st.cols[f.name] && this.source.constraint_tables) {
            bbn.fn.each(st.cols[f.name], k => {
              if (st.keys[k]?.ref_table && this.source.constraint_tables[st.keys[k].ref_table]) {
                const t = st.keys[k].ref_table;
                const c = st.keys[k].ref_column;
                const cols = this.source.constraint_tables[t];
                for (let name in cols) {
                  ar.push({text: f.name + ':' + name, value: f.name + '.' + c + ':' + t + '.'  + name});
                }
              }
            })
          }
        });
        return ar;
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
