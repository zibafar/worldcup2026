<label>کمپین
<select name="campaign_id" required>
    @foreach($campaigns as $campaign)<option value="{{ $campaign->id }}" @selected((string)old('campaign_id', $match?->campaign_id) === (string)$campaign->id)>{{ $campaign->title }}</option>@endforeach
</select></label>
<label>مرحله
<select name="stage_id" required>
    @foreach($stages as $stage)<option value="{{ $stage->id }}" @selected((string)old('stage_id', $match?->stage_id) === (string)$stage->id)>{{ $stage->title }}</option>@endforeach
</select></label>
<label>تیم اول
<select name="home_team_id" required>
    @foreach($teams as $team)<option value="{{ $team->id }}" @selected((string)old('home_team_id', $match?->home_team_id) === (string)$team->id)>{{ $team->name_fa }}</option>@endforeach
</select></label>
<label>تیم دوم
<select name="away_team_id" required>
    @foreach($teams as $team)<option value="{{ $team->id }}" @selected((string)old('away_team_id', $match?->away_team_id) === (string)$team->id)>{{ $team->name_fa }}</option>@endforeach
</select></label>
<label>زمان بازی<input type="datetime-local" name="match_date" value="{{ old('match_date', $match?->match_date?->format('Y-m-d\TH:i')) }}" required></label>
<label>مهلت پیش‌بینی<input type="datetime-local" name="prediction_deadline" value="{{ old('prediction_deadline', $match?->prediction_deadline?->format('Y-m-d\TH:i')) }}"></label>
<label>وضعیت<select name="status" required>@foreach(['scheduled'=>'برنامه‌ریزی‌شده','live'=>'زنده','finished'=>'تمام‌شده','cancelled'=>'لغوشده'] as $key=>$label)<option value="{{ $key }}" @selected(old('status', $match?->status ?? 'scheduled') === $key)>{{ $label }}</option>@endforeach</select></label>
@if($match)
<label>گل تیم اول<input type="number" name="home_score" min="0" max="99" value="{{ old('home_score', $match->home_score) }}"></label>
<label>گل تیم دوم<input type="number" name="away_score" min="0" max="99" value="{{ old('away_score', $match->away_score) }}"></label>
@endif
