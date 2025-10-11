var cleaveDate = new Cleave("#cleave-date", {
    date: !0,
    delimiter: "-",
    datePattern: ["d", "m", "Y"],
});
var cleaveDateFormat = new Cleave("#cleave-date-format", {
    date: !0,
    datePattern: ["m", "y"],
});
var cleaveTime = new Cleave("#cleave-time", {
    time: !0,
    timePattern: ["h", "m", "s"],
});
var cleaveTimeFormat = new Cleave("#cleave-time-format", {
    time: !0,
    timePattern: ["h", "m"],
});
var cleaveNumeral = new Cleave("#cleave-numeral", {
    numeral: !0,
    numeralThousandsGroupStyle: "thousand",
});
var cleaveBlocks = new Cleave("#cleave-ccard", {
    blocks: [4, 4, 4, 4],
    uppercase: !0,
});
var cleaveDelimiter = new Cleave("#cleave-delimiter", {
    delimiter: "·",
    blocks: [3, 3, 3],
    uppercase: !0,
});
var cleaveDelimiters = new Cleave("#cleave-delimiters", {
    delimiters: [".", ".", "-"],
    blocks: [3, 3, 3, 2],
    uppercase: !0,
});
var cleavePrefix = new Cleave("#cleave-prefix", {
    prefix: "PREFIX",
    delimiter: "-",
    blocks: [6, 4, 4, 4],
    uppercase: !0,
});
var cleaveBlocks = new Cleave("#cleave-phone", {
    delimiters: ["(", ")", "-"],
    blocks: [0, 3, 3, 4],
});
