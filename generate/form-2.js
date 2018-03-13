const questions = [
  "Uni",
  "Kestävyyskunto",
  "Terveellinen ruokavalio",
  "Tuki- ja liikuntaelinvaivat",
  "Henkinen jaksaminen",
  "Lihaskunto",
  "Painonhallinta",
  "Liikuntataitojen lisääminen",
  "Liikuntamotivaation lisääminen",
  "Istuminen",
  "Ajanhallinta",
  "Elämäntapamuutoksen tekeminen",
  "Muu, mikä (vastaa alle omin sanoin)"
];

const options = [
  "Panostan nykyään",
  "Haluaisin panostaa"
];

const sections = questions.map((question, pageIndex) => {
  return {
    "title": question,
    "fields": options.map((option, optionIndex) => {
      return {
        "name": getSlug(question + " " + option, {lang: 'fi'}),
        "type": "number",
        "title": option,
        "min": 0,
        "max": 100,
        "class": "slider " + (optionIndex === 0 ? 'service-usage-current' : 'service-usage-desired')
      };
    })
  };
});


const preSections = [];
const postSections = [];

const result = {
  "sections": preSections.concat(sections.concat(postSections))
};

$('#r').val(JSON.stringify(result, null, 2));