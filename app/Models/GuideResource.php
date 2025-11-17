class GuideResource extends Model
{
    protected $fillable = [
        'guide_id', 'cause', 'solution', 'details'
    ];

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }
}
