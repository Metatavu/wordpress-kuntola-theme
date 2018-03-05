const questions = [
  "Ansaitakseni elantoni",
  "Koska se auttaaminua rentoutumaan",
  "Koska se on mielenkiintoista",
  "Jotta voisin liikkua yhdessä muiden kanssa",
  "Kehittyäkseni",
  "Ollakseni paremmassa kunnossa kuin muut",
  "Koska minulle maksetaan siitä",
  "Koska nautin ajanvietosta muiden seurassa liikkuen",
  "Koska haluan hallita stressiä paremmin",
  "Koska sen avulla keho pysyy terveenä",
  "Koska se saa lihakset näyttämään paremmilta",
  "Ollakseni fyysisesti hyvässä kunnossa",
  "Koska se tekee minut onnelliseksi",
  "Unohtaakseni työ/arkihuolet",
  "Ylläpitääkseni fyysistä terveyttäni",
  "Parantaakseni nykyisiä taitojani",
  "Ollakseni ryhmän paras",
  "Hoitaakseni sairautta tai vaivaa",
  "parantaakseni suorituskykyäni verrattuna aikaisempiin suorituksiini",
  "Koska se on yhteistä minulle ja ystävilleni",
  "koska muiden mielestä tarvitsen liikuntaa",
  "koska se toimii stressin laukaisijana",
  "parantaakseni kehoni muotoa",
  "oppiakseni uusia taitoja tai kokeillakseni uusia liikuntamuotoja",
  "Koska se on hauskaa",
  "Lääkärin/fysioterapeutin tms. määräyksestä",
  "Tehdäkseni kuntoni eteen enemmän kuin muut ihmiset",
  "Koska se pitää minut terveenä",
  "Kilpaillakseni muiden kanssa",
  "koska voin samalla keskustella ystävieni kanssa",
  "Pitääkseni yllä nykyistä taitotasoani",
  "parantaakseni ulkonäköäni",
  "Parantaakseni kestävyyskuntoani",
  "koska nautin liikkumisesta",
  "koska liikunta saa ajatukset muualle",
  "Pudottaakseni painoa, jotta näyttäisin paremmalta",
  "koska koen sen hauskana",
  "Ollakseni ystävieni kanssa",
  "Ollakseni paremmassa kunnossa kuin muut",
  "koska se auttaa minua ylläpitämään kehoni jäntevänä"
];

const options = [
  "Täysin eri mieltä",
  "Jokseenkin eri mieltä",
  "En osaa sanoa",
  "Jokseenkin samaa mieltä",
  "Täysin samaa mieltä"
];

const sections = questions.map((question, pageIndex) => {
  return {
    "title": "Miksi harrastat liikuntaa?",
    "visible-if": {
      "field": "page",
      "equals": "" + pageIndex
    },
    "fields": [{
      "name": getSlug(question, {lang: 'fi'}),
      "type": "radio",
      "title": question,
      "options": options.map((option, index) => {
        return {
          name: index + 1,
          text: option
        };
      })
    }]
  };
});


const preSections = [];

const postSections = [{
  "fields": [{
    "type": "html",
    "html": `<input type="hidden" name="page-count" value="${questions.length}"/>`
  }, {
    "type": "hidden",
    "name": "page"
  }]
}];

const result = {
  "sections": preSections.concat(sections.concat(postSections))
};

$('#r').val(JSON.stringify(result, null, 2));